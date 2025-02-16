<?php

namespace core\services;

/**
 * @author Matschieu
 */
class Service {

	private bool $private;
	private ?string $token;
	private array $handlers;

	/**
	 *
	 * @param bool $private
	 * @param string $token
	 */
	public function __construct(bool $private, ?string $token = null) {
		$this->private = $private;
		$this->token = $token;
		$this->handlers = array();

		setlocale(LC_TIME, 'fr_FR');

		$this->registerHandler(HttpMethod::OPTIONS, function() {
			// CORS check
			header("Allow: OPTIONS, ".implode(", ", array_keys($this->handlers)));
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Headers: *");
			http_response_code(204);
			return null;
		});
	}

	/**
	 *
	 * @param HttpMethod $httpMethod
	 * @param callable $handlerFunction
	 * @return Service
	 */
	public function registerHandler(HttpMethod $httpMethod, callable $handlerFunction): Service {
		$this->handlers[$httpMethod->name] = $handlerFunction;
		return $this;
	}

	/**
	 *
	 * @return void
	 */
	public function buildResponse(): void {
		// Authentication
		if ($this->private && (!isset($_SERVER['HTTP_TOKEN']) || $_SERVER['HTTP_TOKEN'] !== $this->token)) {
			http_response_code(403);
			return;
		}

		$notAllowed = true;

		if ($this->handlers != null && count($this->handlers) > 0) {
			foreach ($this->handlers as $httpMethod => $function) {
				if ($_SERVER['REQUEST_METHOD'] != $httpMethod) {
					continue;
				}

				header('Content-type: text/html; charset=utf-8');
				header("Access-Control-Allow-Origin: *");

				// Process service impl
				if ($function != null) {
					$responseContent = $function();

					if ($responseContent != null) {
						echo json_encode($responseContent, JSON_UNESCAPED_UNICODE);
					}
				}

				$notAllowed = false;
				break;
			}
		}

		if ($notAllowed) {
			http_response_code(405);
			return;
		}
	}

}