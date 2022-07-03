<?php

namespace Libsstack\Response;

function respond(string $text = ''): Response {
  return Response::respond($text);
}

function redirect(string $path): Response
{
  return Response::respond(sprintf('Redirecting to %s', $path))->redirect($path);
}