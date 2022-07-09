<?php

namespace Libsstack\Response;

use InvalidArgumentException;

class Response
{
  public function __construct(protected string $response = '', protected int $status = 200, protected array $headers = [])
  {
  }

  public static function __callStatic($name, $arguments)
  {
    $res = new static();

    // Method __call takes over
    $res->$name(...$arguments);

    return $res;
  }

  public function __call($name, $arguments)
  {
    if (in_array($name, ['json', 'redirect', 'notFound', 'respond', 'status', 'type', 'header']))
      return $this->$name(...$arguments);

    throw new InvalidArgumentException(sprintf('Undefined method %s', $name));
  }
  
  protected function respond(string $response): static
  {
    return new Response($response);
  }

  protected function json(array|object $data): static
  {
    $this->type('application/json');
    $this->response = json_encode($data);

    return $this;
  }

  protected function notFound(): static
  {
    $this->status = 404;

    return $this;
  }

  protected function redirect(string $url): static
  {
    $this->status(303);
    $this->respond(sprintf('Redirecting to %s', $url));
    $this->header('Location', $url);

    return $this;
  }

  protected function header(array|string $header, string $value = null): static
  {
    if (is_string($header))
      $this->headers[$header] = $value;
    else
      $this->headers = array_merge($this->headers, $header);

    return $this;
  }

  protected function status(int $status): static
  {
    $this->status = $status;

    return $this;
  }

  protected function type(string $type): static
  {
    $this->header('Content-Type', $type);

    return $this;
  }

  public function send(): void
  {
    http_response_code($this->status);

    foreach ($this->headers as $header => $value) {
      header(sprintf('%s: %s', $header, $value));
    }

    echo $this->response;
  }
}