<?php

namespace Blaze\Myst\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * CacheService constructor.
     */
    public function __construct()
    {
        $this->removeExpiredMystConversations();
    }
    
    /**
     * Fetch all conversations from cache
     *
     * @return mixed
     */
    public function getMystConversationsCache()
    {
        return Cache::get(ConfigService::getConversationCacheKey());
    }
    
    /**
     * Save conversations to Cache
     *
     * @param array $data
     */
    public function saveMystConversationsCache(array $data)
    {
        Cache::forever(ConfigService::getConversationCacheKey(), $data);
    }
    
    /**
     * Remove all expired conversations from the cache
     */
    protected function removeExpiredMystConversations()
    {
        $myst_conversations = $this->getMystConversationsCache();
        foreach ($myst_conversations as $chat_id => $chat) {
            foreach ($chat as $user_id => $user) {
                if (Carbon::parse($user['expires_at'])->lessThanOrEqualTo(Carbon::now())) {
                    unset($myst_conversations[$chat_id][$user_id]);
                }
            }
            if (count($chat) < 1) {
                unset($myst_conversations[$chat_id]);
            }
        }
        $this->saveMystConversationsCache($myst_conversations);
    }
}
