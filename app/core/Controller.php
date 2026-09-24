<?php
/**
 * Base controller. Controllers stay thin:
 * read and validate input, call a service, return a view or JSON.
 * No SQL and no business rules here.
 */
abstract class Controller
{
    public function __construct(protected Request $request)
    {
    }

    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function json(array $data, int $status = 200): void
    {
        Response::json($data, $status);
    }

    protected function redirect(string $path): void
    {
        Response::redirect($path);
    }

    /** Record missing or owned by someone else: 404 page, or JSON on /api/ routes. */
    protected function notFound(): never
    {
        if ($this->request->isApi()) {
            Response::json(['error' => 'Not found.'], 404);
        }
        http_response_code(404);
        View::render('errors/404');
        exit;
    }

    /** Used by the login and sign-up pages, which have no use for a logged-in user. */
    protected function redirectIfLoggedIn(): void
    {
        if (Auth::check()) {
            $this->redirect(Auth::homeUrl());
        }
    }

    /** Call at the start of every POST/PUT/DELETE action. */
    protected function verifyCsrf(): void
    {
        $token = $this->request->input('_csrf') ?? $this->request->header('X-CSRF-Token');
        if (!Session::validCsrf($token)) {
            if ($this->request->isApi()) {
                Response::json(['error' => 'Invalid or missing CSRF token. Refresh the page and try again.'], 403);
            }
            http_response_code(403);
            exit('Your session expired. Please go back and try again.');
        }
    }
}
