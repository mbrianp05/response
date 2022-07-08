<?php

namespace Libsstack\Response;

class Response
{
  public function __construct(protected string $response = '', protected int $status = 200, protected array $headers = [])
  {
  }
  
  public static function respond(string $response): static
  {
    return new Response($response);
  }

  public function json(array|object $data): static
  {
    $this->type('application/json');
    $this->response = json_encode($data);

    return $this;
  }

  public function notFound(): static
  {
    $this->status = 404;

    return $this;
  }

  public function redirect(string $url): static
  {
    $this->status = 303;
    $this->withHeader('Location', $url);

    return $this;
  }

  public function withHeader(array|string $header, string $value = null): static
  {
    if (is_string($header))
      $this->headers[$header] = $value;
    else
      $this->headers = array_merge($this->headers, $header);

    return $this;
  }

  public function status(int $status): static
  {
    $this->status = $status;

    return $this;
  }

  public function type(string $type): static
  {
    $this->withHeader('Content-Type', $type);

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