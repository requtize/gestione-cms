<?php

namespace Gestione\Cms\Content\Page\Query;

use Gestione\Component\DBAL\DatabaseInterface;
use Gestione\Component\I18n\LocaleInterface;
use Gestione\Cms\Content\Page\Page;
use Gestione\Cms\Content\Page\Collection\CollectionInterface;
use Gestione\Cms\Content\Page\Collection\Collection;

class Query implements QueryInterface
{
    protected $database;
    protected $locale;

    protected $selects  = [];
    protected $joins    = [];
    protected $wheres   = [ '1 = 1' ];
    protected $orderbys = [];
    protected $groupbys = [];
    protected $limitOffset = null;
    protected $limitNumber = null;

    protected $binds = [];

    public function __construct(DatabaseInterface $database, LocaleInterface $locale)
    {
        $this->database = $database;
        $this->locale   = $locale;
    }

    public function find($id): CollectionInterface
    {
        return $this->queryRaw([
            'id' => $id,
            'older_than'  => 'now',
            'visibility' => 1
        ]);
    }

    public function findBySlug($slug): CollectionInterface
    {
        return $this->queryRaw([
            'slug' => $slug,
            'older_than'  => 'now',
            'visibility' => 1
        ]);
    }

    public function query(array $query): CollectionInterface
    {
        return $this->execute(array_merge([
            'id'          => null,
            'slug'        => null,
            'page_type'   => [ 'page' ],
            'page_status' => [ 'published' ],
            'older_than'  => 'now',
            'visibility'  => 1,
            'offset'      => null,
            'per_page'    => null,
        ], $query));
    }

    public function queryRaw(array $query): CollectionInterface
    {
        return $this->execute(array_merge([
            'id'          => null,
            'slug'        => null,
            'page_type'   => [],
            'page_status' => [],
            'older_than'  => null,
            'visibility'  => null,
            'offset'      => null,
            'per_page'    => null,
        ], $query));
    }

    public function execute(array $query): CollectionInterface
    {
        $this->searchById($query);
        $this->searchBySlug($query);
        $this->buildPageType($query);
        $this->buildPageStatus($query);
        $this->buildDate($query);
        $this->buildVisibility($query);
        $this->buildOffset($query);
        $this->setDefaults($query);

        if($this->selects === [])
            $this->selects[] = '*';

        $sql = 'SELECT '.implode(', ', $this->selects).' FROM `'.$this->database->getTablePrefix().'page` AS tm';

        if($this->joins !== [])
            $sql .= ' '.implode(' ', $this->joins);

        if($this->wheres !== [])
            $sql .= ' WHERE '.implode(' AND ', $this->wheres);

        if($this->limitOffset)
            $sql .= ' LIMIT '.$this->limitOffset;

        if($this->limitNumber)
            $sql .= ', '.$this->limitNumber;

        //dump($sql, $this->binds);

        $statement = $this->database->prepare($sql);

        foreach($this->binds as $key => $val)
        {
            $statement->bindValue($key, $val);
        }

        $statement->execute();

        $result = $statement->fetchAll();
        $collection = new Collection;

        if($result === [])
            return $collection;

        foreach($result as $row)
        {
            $collection->append(Page::fromArray($row));
        }

        return $collection;
    }

    protected function setDefaults(array $query)
    {
        $this->selects[] = 'tm.*';
        $this->selects[] = 'tl.*';
        $this->joins[] = 'INNER JOIN `'.$this->database->getTablePrefix().'page_lang` AS tl ON (tm.id = tl.page_id)';
        $this->wheres[] = 'tl.locale = :tl_locale';
        $this->binds[':tl_locale'] = $this->locale->getCode();
    }

    protected function searchById(array $query)
    {
        if(! $query['id'])
            return;

        $this->wheres[] = 'tm.id = :tm_id';
        $this->binds[':tm_id']  = $query['id'];
        $this->limitOffset = 1;
        $this->limitNumber = 0;
    }

    protected function searchBySlug(array $query)
    {
        if(! $query['slug'])
            return;

        $this->wheres[] = 'tl.slug = :tl_slug';
        $this->binds[':tl_slug']  = $query['slug'];
        $this->limitOffset = 1;
        $this->limitNumber = 0;
    }

    protected function buildPageType(array $query)
    {
        if(is_array($query['page_type']))
            $types = $query['page_type'];
        else
            $types = [ $query['page_type'] ];

        if($types === [])
            return;

        $where = [];

        foreach($types as $type)
        {
            $where[] = $this->database->quote($type);
        }

        $this->wheres[] = 'tm.type IN('.implode(', ', $where).')';
    }

    protected function buildPageStatus(array $query)
    {
        if(is_array($query['page_status']))
            $statuses = $query['page_status'];
        else
            $statuses = [ $query['page_status'] ];

        if($statuses === [])
            return;

        $where = [];

        foreach($statuses as $status)
        {
            $where[] = $this->database->quote($status);
        }

        $this->wheres[] = 'tm.status IN('.implode(', ', $where).')';
    }

    protected function buildDate(array $query)
    {
        if(! $query['older_than'])
            return;

        if($query['older_than'] === 'now')
            $query['older_than'] = date('Y-m-d H:i:s');
        else
            $query['older_than'] = strtotime('Y-m-d H:i:s', $query['older_than']);

        $this->wheres[] = 'tm.published_at <= '.$this->database->quote($query['older_than']);
    }

    protected function buildVisibility(array $query)
    {
        if(! $query['visibility'])
            return;

        $this->wheres[] = 'tl.visibility = '.$this->database->quote($query['visibility']);
    }

    protected function buildOffset(array $query)
    {
        if($query['offset'])
        {
            $this->limitOffset = $query['offset'];
            $this->limitNumber = 0;
        }
        if($query['offset'] && $query['per_page'])
        {
            $this->limitOffset = $query['offset'];
            $this->limitNumber = $query['per_page'];
        }

        $this->wheres[] = 'tl.visibility = '.$this->database->quote($query['visibility']);
    }
}
