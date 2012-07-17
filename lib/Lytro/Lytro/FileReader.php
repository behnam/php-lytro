<?php

namespace Lytro;

class InvalidFileException extends \Exception {}
class InvalidContents extends \Exception {}
class InvalidMetaData extends \Exception {}

class FileReader
{
    protected $name;
    protected $size;
    protected $cursor;
    protected $data = array(
        'meta' => null,
        'lookup' => array(),
        'images' => array(),
    );

    public function __construct($filePath)
    {
        $this->name = $filePath;
        $this->size = filesize($filePath);
        $this->cursor = fopen($this->name, 'r');

        if (!$this->isValidType()) {
            throw new InvalidFileException(sprintf('', $filePath));
        }

        $this->findMetaData();

        while (!feof($this->cursor)) {
            $this->findContents();
        }
    }

    public function getImages()
    {
        return $this->data['images'];
    }

    public function getLookupTable()
    {
        return $this->data['lookup'];
    }

    public function getMetaData()
    {
        return $this->data['meta'];
    }

    private function findContents()
    {
        $data = $this->captureContents();

        $sha1 = trim(substr($data, 14, 40));
        $data = rtrim(substr($data, 14 + 40 + 35));
        if ($sha1 != sha1($data)) {
            throw new InvalidContents(sprintf('SHA1 checksum mismatch for: %s', $sha1));
        }

        if ($this->isValidJpeg($data)) {;
            $this->data['images'][$sha1] = $data;
        } else {
            $this->createLookupTable($data);
        }
    }

    private function createLookupTable($data)
    {
        for ($i = 0; $i < (strlen($data)/4); $i++) {
            $index = $i * 4;
            $value = substr($data, $index, 4);

            if (strlen($value) != 4) {
                continue;
            }

            $value = unpack('f*', $value);
            $this->data['lookup'][] = sprintf('%f', $value[1]);
        }
    }

    private function findMetaData()
    {
        $this->skipHeader(0x89, 0x4C, 0x46, 0x4D, 0x0D, 0x0A, 0x1A, 0x0A);

        $data = $this->captureContents();

        $sha1 = trim(substr($data, 14, 40));
        $data = trim(substr($data, 35 + 45));

        if ($sha1 != sha1($data)) {
            throw new InvalidMetaData('The metadata doesn\'t match the SHA1 checksum.');
        }

        $this->data['meta'] = json_decode($data, true);
        unset($data);
    }

    private function isValidJpeg($data)
    {
        $magic = $this->magicHeader(0xFF, 0xD8, 0xFF, 0xE0, 0x00, 0x10, 0x4A, 0x46, 0x49, 0x46);
        return !!($magic == substr($data, 0, strlen($magic)));
    }

    private function isValidType()
    {
        $magic = $this->magicHeader(0x89, 0x4C, 0x46, 0x50, 0x0D, 0x0A, 0x1A, 0x0A);

        if ($this->size > strlen($magic) && $magic === fread($this->cursor, strlen($magic))) {
            return true;
        }

        return false;
    }

    private function captureContents()
    {
        $magic = $this->magicHeader(0x89, 0x4C, 0x46, 0x43, 0x0D, 0x0A, 0x1A, 0x0A);
        $cursor = ftell($this->cursor);
        $data = '';

        do {
            $buffer = fread($this->cursor, strlen($magic));

            if ($buffer == $magic) {
                break;
            }

            $data .= substr($buffer, 0, 1);
        } while (!feof($this->cursor) && (fseek($this->cursor, $cursor++) === 0));

        if (feof($this->cursor)) {
            $data .= substr($buffer, 1, strlen($magic));
        }

        return $data;
    }

    private function skipHeader()
    {
        $bytes = func_get_args();
        $magic = call_user_func_array(array($this, 'magicHeader'), $bytes);
        $cursor = ftell($this->cursor);

        do {
            $buffer = fread($this->cursor, strlen($magic));

            if ($buffer == $magic) {
                break;
            }
        } while (!feof($this->cursor) && (fseek($this->cursor, $cursor++) === 0));
    }

    private function magicHeader()
    {
        $bytes = func_get_args();

        foreach ($bytes as $index => $byte) {
            $bytes[$index] = chr($byte);
        }

        return implode('', $bytes);
    }
}