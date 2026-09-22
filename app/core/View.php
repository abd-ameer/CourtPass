<?php
/**
 * Renders app/views/<view>.php inside app/views/layouts/<layout>.php.
 * The view's output is available to the layout as $content.
 * Views display data only: no queries, always escape with e().
 */
class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        $content = self::capture($view, $data);

        if ($layout === null) {
            echo $content;
            return;
        }
        echo self::capture('layouts/' . $layout, array_merge($data, ['content' => $content]));
    }

    public static function capture(string $view, array $data = []): string
    {
        $file = VIEW_PATH . '/' . $view . '.php';
        if (!is_file($file)) {
            throw new RuntimeException("View not found: {$view}");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    }

    /** Include a partial from inside a view: View::partial('nav'). */
    public static function partial(string $name, array $data = []): void
    {
        echo self::capture('partials/' . $name, $data);
    }
}
