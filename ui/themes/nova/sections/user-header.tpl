  <!-- This is added under ui>themes>nova>sections  Please note this is just a snippet of the header replace the message area only -->
 <!-- BEGIN: Message Dropdown -->
<div class="relative md:block">
  <button
    class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer
    rounded-full text-[20px] flex flex-col items-center justify-center"
    type="button"
    data-bs-toggle="dropdown"
    aria-expanded="false"
  >
    <iconify-icon class="text-slate-800 dark:text-white text-xl" icon="heroicons-outline:mail"></iconify-icon>
    <!-- Notification Count -->
    {if $unread_count > 0}
    <span
      class="absolute -right-1 lg:top-0 -top-[6px] h-4 w-4 bg-red-500 text-[8px] font-semibold flex flex-col items-center
      justify-center rounded-full text-white z-[45]"
    >
      {$unread_count}
    </span>
    {/if}
  </button>
  <!-- Messages Dropdown Menu -->
  <div
    class="dropdown-menu z-10 hidden bg-white divide-y divide-slate-100 shadow w-80 dark:bg-slate-800 border dark:border-slate-700 !top-[23px] rounded-md
    overflow-hidden"
  >
    <div class="py-2">
      <!-- Display latest messages -->
      {foreach from=$latest_messages item=message}
      <a href="{$_url}user_messages/view/{$message.id}" class="flex items-start px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-600">
        <div class="flex-1">
          <p class="text-sm text-slate-800 dark:text-white font-semibold">{$message.title}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{$message.content|truncate:50:"...":true}</p>
          <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{$message.date}</p>
        </div>
        {if $message.unread}
        <span class="ml-2 text-blue-500 text-xs">{Lang::T('Unread')}</span>
        {/if}
      </a>
      {/foreach}
    </div>
    <div class="py-2 text-center">
      <a href="{$_url}user_messages/inbox" class="text-sm text-blue-500">{Lang::T('View All Messages')}</a>
    </div>
  </div>
</div>
<!-- END: Message Dropdown -->
