<?php

namespace Libsstack\Response;

function respond(string $text = ''): Response
{
  return Response::respond($text);
}

function json(array|object $data): Response
{
  return (new Response())->json($data);
}

function redirect(string $path): Response
{
  return Response::respond(sprintf('Redirecting to %s', $path))->redirect($path);
}
