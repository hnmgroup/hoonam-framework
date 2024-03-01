<?php

namespace Hoonam\Framework\Infrastructure;

use DateTime;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Morilog\Jalali\Jalalian;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Carbon\Carbon as BaseCarbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Hoonam\Framework\NotSupportedException;
use PhpOffice\PhpSpreadsheet\IOFactory;

abstract class ExportService
{
    private array $data;
    private array $options;
    private array $fieldOption;

    public function setOptions(array $options): self
    {
        $this->options = $options;
        $this->fieldOption = arrayToMap($options['columns'] ?? [], 'name');
        return $this;
    }

    protected function resolveFieldValue(string $field, mixed $value): mixed
    {
        $values = $this->getFieldOption($field, 'values');
        if (isset($values)) {
            $value = getValue(Arr::first($values, fn ($o) => ($o['value'] ?? null) == $value), 'title');
        }

        $format = $this->getFieldOption($field, 'format');
        if (isset($format)) {
            $locale = $this->getFieldOption($field, 'locale');
            $value = $this->formatValue($value, $format, $locale);
        }

        return $value;
    }

    private function formatValue(mixed $value, string $format, ?string $locale): mixed
    {
        if (!isset($value)) return $value;

        else if ($value instanceof Carbon) {
            $value = $this->formatDateTime($value, $format, $locale);
        }

        else if ($value instanceof BaseCarbon) {
            return $this->formatValue(Date::parse($value), $format, $locale);
        }

        else if ($value instanceof DateTime) {
            return $this->formatValue(Date::parse($value), $format, $locale);
        }

        return $value;
    }

    private function formatDateTime(Carbon $value, string $format, ?string $locale): string
    {
        $locale ??= 'en';
        return match ($locale) {
            'en'    => $value->format($format),
            'fa'    => Jalalian::fromCarbon($value)->format($format),
            default => throw new NotSupportedException("locale '$locale' not supported")
        };
    }

    private function getFieldOption(string $name, string $option, $default = null): mixed
    {
        return getValue($this->fieldOption, $name.'.'.$option, $default);
    }

    public function setData(array $data): self
    {
        $this->data = Arr::map($data, fn ($row) => $this->map($row));
        return $this;
    }

    protected function map($data): array
    {
        return is_array($data) ? $data : (array)$data;
    }

    public function title(): string
    {
        return $this->options['title'] ?? 'Title';
    }

    public function headings(): array
    {
        return [];
    }

    public function rightToLeft(): ?bool
    {
        return null;
    }

    public function columnFormats(): array
    {
        return [];
    }

    private function getFormat(): string
    {
        $format = $this->options['format'];
        switch ($format) {
            case 'excel':
            case 'csv':
                return $format;
        }
        throw new NotSupportedException("format '$format' not supported");
    }

    private function getExtension(): string
    {
        return match ($this->getFormat()) {
            'excel' => '.xlsx',
            'csv'   => '.csv',
            default => ''
        };
    }

    public function fileName(): string
    {
        return ($this->options['fileName'] ?? $this->title()).$this->getExtension();
    }

    public function contentType(): string
    {
        return match ($this->getExtension()) {
            '.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            '.csv'  => 'text/csv',
            default => 'application/octet-stream'
        };
    }

    public function exportContent(): string
    {
        return match ($this->getFormat()) {
            'excel' => $this->exportExcelContent(),
            'csv'   => $this->exportCsvContent(),
            default => throw new NotSupportedException
        };
    }

    protected function excelStartCell(): string
    {
        return 'A1';
    }

    protected function excelTemplateFile(): ?string
    {
        return null;
    }

    private function exportExcelContent(): string
    {
        if (!is_null($this->excelTemplateFile())) {
            return $this->exportFromTemplate($this->excelTemplateFile());
        }

        $export = new class (
            $this->data,
            $this->excelStartCell(),
            $this->title(),
            $this->headings(),
            $this->columnFormats(),
            $this->rightToLeft(),
        ) implements
            FromArray,
            WithStrictNullComparison,
            WithHeadings,
            WithColumnFormatting,
            WithEvents,
            WithCustomStartCell
        {
            public function __construct(
                private readonly array $data,
                private readonly string $startCell,
                private readonly ?string $title = null,
                private readonly array $headings = [],
                private readonly array $columnFormats = [],
                private readonly ?bool $rtl = null,
            ) {}

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return $this->headings;
            }

            public function columnFormats(): array
            {
                return $this->columnFormats;
            }

            public function startCell(): string
            {
                return $this->startCell;
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        if (isset($this->rtl)) $event->getSheet()->setRightToLeft($this->rtl);
                        if (isset($this->title)) $event->getSheet()->setTitle($this->title);
                    },
                ];
            }
        };

        $content = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        return $content;
    }

    private function exportFromTemplate(string $templateFile): string
    {
        $reader = IOFactory::createReader(IOFactory::READER_XLSX);
        $spreadsheet = $reader->load($templateFile);
        $sheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $sheet->fromArray(
            $this->data,
            startCell: $this->excelStartCell(),
            strictNullComparison: true,
        );
        $sheet->setTitle($this->title());
        $writer = IOFactory::createWriter($spreadsheet, IOFactory::READER_XLSX);
        $content = tempFile(function ($file, $filePath) use ($writer) {
            $writer->save($filePath);
            return File::get($filePath);
        });

        return $content;
    }

    private function exportCsvContent(): string
    {
        throw new NotSupportedException('CSV format export not supported');
    }
}
