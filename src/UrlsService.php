<?php

namespace Thaldos\FindAccountsByUsername;

use RateLimit\ApcuRateLimiter;
use RateLimit\Rate;
use RateLimit\Status;

class UrlsService
{
    const NUMBER_MAX_REQUESTS_PER_MINUTE = 10;

    public function getUrls()
    {
        $result = [];

        // Protect against brute force attack :
        if (!$this->isBruteForceAttack() && isset($_POST['username']) && is_string($_POST['username'])) {
            // Get current project path :
            $projectPath = shell_exec('pwd');
            if (is_string($projectPath)) {
                // Get response from Sherlock project :
                $projectPath = trim($projectPath);
                $command = 'python3 ' . $projectPath . '/../src/sherlock/sherlock ' . $_POST['username'] . ' --timeout 15 --output '
                    . $projectPath . '/../results/' . time() . '.txt';
                $output = shell_exec($command);
                if (is_string($output)) {
                    // Transform the Sherlock'soutput to json :
                    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $output, $match);
                    $urls = reset($match);
                    if ($urls !== false) {
                        $result = $urls;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Return the current user ip.
     */
    private function getIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
    }

    /**
     * Increase the counter of requests for this IP,
     * and return true if the counter is less than NUMBER_MAX_REQUESTS_PER_MINUTE, false otherwise.
     * The counter is reset each minute.
     */
    private function isBruteForceAttack(): bool
    {
        $rest = 0;

        $rateLimiter = new ApcuRateLimiter(Rate::perMinute(self::NUMBER_MAX_REQUESTS_PER_MINUTE), 'find-accounts-by-username-api');
        $status = $rateLimiter->limitSilently($this->getIp());
        if ($status instanceof Status) {
            $rest = $status->getRemainingAttempts();
        }

        return $rest < 0;
    }
}
