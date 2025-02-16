<?php

namespace core\services;

enum HttpMethod {
	case GET;
	case POST;
	case PUT;
	case DELETE;
	case UPDATE;
	case OPTIONS;
}
