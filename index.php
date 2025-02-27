<?php
session_start();
require 'vendor/autoload.php';

use Bramus\Router\Router;

use Dotenv\Dotenv;

const ROOT_PATH = __DIR__;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require "route.php";
