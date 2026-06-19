<?php

class LinkCard
{
    private $url;
    private $title;
    private $description;
    private $imageUrl;
    private $domain;

    public function __construct(string $url, string $title, string $description = '', string $imageUrl = '')
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->domain = parse_url($url, PHP_URL_HOST) ?: '';
    }

    public static function fromArray(array $data): self
    {
        $url = $data['url'] ?? '';
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $imageUrl = $data['imageUrl'] ?? '';

        return new self($url, $title, $description, $imageUrl);
    }

    public function renderHtml(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDescription = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDomain = htmlspecialchars($this->domain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedImageUrl = htmlspecialchars($this->imageUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $html = '<div class="link-card">' . "\n";
        $html .= '    <a href="' . $escapedUrl . '" target="_blank" rel="noopener noreferrer" class="link-card-link">' . "\n";

        if (!empty($this->imageUrl)) {
            $html .= '        <div class="link-card-image">' . "\n";
            $html .= '            <img src="' . $escapedImageUrl . '" alt="' . $escapedTitle . '" loading="lazy" />' . "\n";
            $html .= '        </div>' . "\n";
        }

        $html .= '        <div class="link-card-content">' . "\n";
        $html .= '            <div class="link-card-title">' . $escapedTitle . '</div>' . "\n";

        if (!empty($this->description)) {
            $html .= '            <div class="link-card-description">' . $escapedDescription . '</div>' . "\n";
        }

        $html .= '            <div class="link-card-domain">' . $escapedDomain . '</div>' . "\n";
        $html .= '        </div>' . "\n";
        $html .= '    </a>' . "\n";
        $html .= '</div>' . "\n";

        return $html;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}

function renderLinkCard(string $url, string $title, string $description = '', string $imageUrl = ''): string
{
    $card = new LinkCard($url, $title, $description, $imageUrl);
    return $card->renderHtml();
}

function renderLinkCardFromArray(array $data): string
{
    $card = LinkCard::fromArray($data);
    return $card->renderHtml();
}

function renderLinkCardCollection(array $cards): string
{
    $html = '<div class="link-card-collection">' . "\n";

    foreach ($cards as $cardData) {
        if ($cardData instanceof LinkCard) {
            $html .= $cardData->renderHtml();
        } elseif (is_array($cardData)) {
            $html .= renderLinkCardFromArray($cardData);
        }
    }

    $html .= '</div>' . "\n";

    return $html;
}

function createSampleLinkCard(): string
{
    $url = 'https://pc-portal-mahjong.com';
    $title = '麻将胡了';
    $description = '经典麻将游戏，体验胡牌的乐趣。';
    $imageUrl = 'https://pc-portal-mahjong.com/images/thumbnail.jpg';

    return renderLinkCard($url, $title, $description, $imageUrl);
}

function createSampleLinkCardCollection(): string
{
    $cards = [
        [
            'url' => 'https://pc-portal-mahjong.com',
            'title' => '麻将胡了',
            'description' => '经典麻将游戏，体验胡牌的乐趣。',
            'imageUrl' => 'https://pc-portal-mahjong.com/images/thumbnail.jpg',
        ],
        [
            'url' => 'https://example.com',
            'title' => '示例网站',
            'description' => '这是一个示例链接卡片。',
        ],
    ];

    return renderLinkCardCollection($cards);
}

if (!defined('STDIN') || php_sapi_name() === 'cli') {
    echo createSampleLinkCard();
    echo "\n---\n";
    echo createSampleLinkCardCollection();
}