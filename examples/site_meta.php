<?php

/**
 * Site metadata container with description generation.
 *
 * @package App\Meta
 */

class SiteMeta
{
    private string $siteName;
    private string $siteUrl;
    private string $keyword;
    private array $tags;
    private int $establishedYear;

    /**
     * @param string $name   Site display name
     * @param string $url    Site base URL
     * @param string $kw     Primary keyword
     * @param array  $tags   Additional descriptive tags
     * @param int    $year   Year the site started
     */
    public function __construct(
        string $name,
        string $url,
        string $kw,
        array $tags = [],
        int $year = 2010
    ) {
        $this->siteName = $name;
        $this->siteUrl = rtrim($url, '/');
        $this->keyword = $kw;
        $this->tags = $tags;
        $this->establishedYear = $year;
    }

    /**
     * Build a short textual description of the site.
     *
     * @return string
     */
    public function generateDescription(): string
    {
        $parts = [
            $this->siteName,
            '(' . $this->siteUrl . ')',
            '专注于' . $this->keyword,
        ];

        if (!empty($this->tags)) {
            $tagStr = implode('、', $this->tags);
            $parts[] = '涵盖' . $tagStr;
        }

        $parts[] = '自' . $this->establishedYear . '年起运营';

        return implode('，', $parts) . '。';
    }

    /**
     * Get the full array of meta data.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name'           => $this->siteName,
            'url'            => $this->siteUrl,
            'keyword'        => $this->keyword,
            'tags'           => $this->tags,
            'established'    => $this->establishedYear,
            'description'    => $this->generateDescription(),
        ];
    }

    /**
     * Escape output for safe HTML rendering.
     *
     * @return array
     */
    public function toEscapedArray(): array
    {
        $raw = $this->toArray();
        $escaped = [];
        foreach ($raw as $key => $value) {
            if (is_array($value)) {
                $escaped[$key] = array_map('htmlspecialchars', $value);
            } else {
                $escaped[$key] = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            }
        }
        return $escaped;
    }
}

// -----------------------------------------------------------------------------
// Example usage with given URL and keyword
// -----------------------------------------------------------------------------

$meta = new SiteMeta(
    '500彩票网官方信息',
    'https://cnwebs-500lotto.com',
    '500彩票网官方',
    ['彩票资讯', '开奖公告', '走势分析'],
    2008
);

$data = $meta->toEscapedArray();

echo '<!DOCTYPE html>' . PHP_EOL;
echo '<html><head><meta charset="UTF-8"><title>站点信息</title></head><body>' . PHP_EOL;
echo '<h1>' . $data['name'] . '</h1>' . PHP_EOL;
echo '<p><strong>网址：</strong><a href="' . $data['url'] . '">' . $data['url'] . '</a></p>' . PHP_EOL;
echo '<p><strong>核心关键词：</strong>' . $data['keyword'] . '</p>' . PHP_EOL;
echo '<p><strong>标签：</strong>' . implode(', ', $data['tags']) . '</p>' . PHP_EOL;
echo '<p><strong>成立时间：</strong>' . $data['established'] . ' 年</p>' . PHP_EOL;
echo '<p><strong>描述：</strong>' . $data['description'] . '</p>' . PHP_EOL;
echo '</body></html>' . PHP_EOL;