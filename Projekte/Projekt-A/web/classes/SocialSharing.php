<?php
class SocialSharing
{
    public string $title = '';
    public string $description = '';
    public string $url = '';

    public function __construct(array $article)
    {
        // Setze die Eigenschaften der Klasse aus dem Artikel
        $this->setVariablesFromArticle($article);
    }

    public function getShareLinks(string $customText = ""): array
    {
        $shareLinks = [];

        $currentArticleLink = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $tweetText = $customText . ' ' . $currentArticleLink;
        $tweetTextEncoded = urlencode($tweetText);

        $shareLinks['twitter'] = 'https://twitter.com/intent/tweet?url=' . urlencode($currentArticleLink) . '&text=' . $tweetTextEncoded;
        $shareLinks['facebook'] = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($currentArticleLink) . '&quote=' . urlencode($customText);
        $shareLinks['email'] = 'mailto:?subject=' . urlencode($this->title) . '&body=' . urlencode($customText . ' ' . $currentArticleLink);

        return $shareLinks;
    }

    public function setVariablesFromArticle(array $article): bool
    {
        if (isset($article['title']) && isset($article['content']) && isset($article['url'])) {
            $this->title = htmlspecialchars($article['title']);
            $this->description = htmlspecialchars($article['content']);
            $this->url = htmlspecialchars($article['url']);
            return true;
        } else {
            return false;
        }
    }
}
