<?php

namespace Spatie\DevelopmentDashboard;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SplFileInfo;

class Report
{
    /** @var \Symfony\Component\Finder\SplFileInfo */
    protected $file;

    public static function all(int $createdAfterTimestamp = null): Collection
    {
        return collect(File::allFiles(config('development-dashboard.storage.path')))
            ->sortByDesc(function (SplFileInfo $file) {
                return $file->getCTime();
            })
            ->filter(function (SplFileInfo $file) use ($createdAfterTimestamp) {
                if (is_null($createdAfterTimestamp)) {
                    return true;
                }

                return $file->getCTime() >= $createdAfterTimestamp;
            })
            ->map(function (SplFileInfo $file) {
                return new static($file);
            })
            ->values();
    }

    public static function createFromData(array $data): Report
    {
        $fileName = 'report-' . date('Ymd-his') . '-' . Str::uuid() . '.json';

        $fullPath = config('development-dashboard.storage.path') . '/' . $fileName;

        file_put_contents($fullPath, json_encode($data));

        return new static(new SplFileInfo($fullPath));
    }

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function createdAt(): int
    {
        return $this->file->getCTime();
    }

    public function content(): array
    {
        $fileContent =  file_get_contents($this->file->getPathname());

        return json_decode($fileContent, true);
    }
}