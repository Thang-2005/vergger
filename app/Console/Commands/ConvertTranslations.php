<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConvertTranslations extends Command
{
    protected $signature = 'translate:convert';
    protected $description = 'Convert all __("messages.xxx") to Vietnamese strings';

    public function handle()
    {
        $messagesFile = resource_path('lang/vi/messages.php');
        $translations = include $messagesFile;

        $this->info('Bắt đầu chuyển đổi...');

        $dir = resource_path('views');
        $files = $this->getAllBladeFiles($dir);

        $this->info('Tìm thấy ' . count($files) . ' file');

        $count = 0;
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $originalContent = $content;

            // Thay thế tất cả __('messages.xxx') bằng regex
            $content = preg_replace_callback(
                "/(__\\(['\"]messages\\.([a-z0-9_]+)['\"]\\))/",
                function($matches) use ($translations) {
                    $key = $matches[2];
                    if (isset($translations[$key])) {
                        return "'" . addslashes($translations[$key]) . "'";
                    }
                    return $matches[0];
                },
                $content
            );

            if ($content !== $originalContent) {
                file_put_contents($file, $content);
                $count++;
                $this->line('✓ ' . str_replace(base_path() . '/', '', $file));
            }
        }

        $this->info("\n✅ Đã chuyển đổi $count file!");
    }

    private function getAllBladeFiles($dir)
    {
        $files = [];
        if (!is_dir($dir)) return $files;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }
}
