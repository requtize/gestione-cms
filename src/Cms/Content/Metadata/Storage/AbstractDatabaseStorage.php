<?php

namespace Gestione\Cms\Content\Metadata\Storage;

use Gestione\Component\DBAL\Database;
use Gestione\Component\I18n\LocaleInterface;
use Gestione\Component\Hooking\HookerInterface;

abstract class AbstractDatabaseStorage extends AbstractStorage
{
    protected $database;
    protected $locale;
    protected $hooker;

    public function __construct(Database $database, LocaleInterface $locale, HookerInterface $hooker)
    {
        $this->database = $database;
        $this->locale   = $locale;
        $this->hooker   = $hooker;
    }

    abstract public function getContentType(): string;

    public function get($contentId, string $name, $default = null)
    {
        return $this->getContentType().'.'.$contentId.'.'.$name;
    }

    public function getMany($contentId, array $names): array
    {
        return $this->query($contentId, $names);
    }

    public function set($contentId, string $name, $value)
    {

    }

    public function delete($contentId, string $name)
    {

    }

    public function query($contentId, array $names)
    {
        $count = count($names);
        $namesPrepared = array_map(function ($value) {
            return $this->database->quote($value);
        }, $names);
        $namesPrepared = implode(',', $namesPrepared);

        $sql = "SELECT * FROM #__metadata AS tm
        INNER JOIN #__metadata_lang AS tl
        ON (
            tm.id = tl.metadata_id
        )
        WHERE (
            1 = 1
            AND tm.content_id = :content_id
            AND tm.name IN ({$namesPrepared})
            AND tm.content_type = :content_type
            AND tl.locale = :locale
        )
        LIMIT {$count}";

        $source = $this->database->query($sql, [
            ':locale' => $this->locale,
            ':content_id' => $contentId,
            ':content_type' => $this->getContentType()
        ]);

        $result = [];

        foreach($source as $row)
        {
            $result[$row['name']] = $row['value'];
        }

        foreach($names as $name)
        {
            if(isset($result[$name]) === false)
            {
                $result[$name] = null;
            }
        }

        return $result;
    }
}
