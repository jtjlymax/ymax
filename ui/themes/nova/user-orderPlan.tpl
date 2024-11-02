{include file="sections/user-header.tpl"}
 
<!-- user-orderPlan.tpl -->
 
<!-- Check if the active plan exists and display it -->
{if isset($active_plan)}
  <div class="space-y-5">
    <div class="card">
      <header class="card-header">
        <div class="card-title">
          Active Plan: {$active_plan.name_plan|default:'N/A'}
        </div>
      </header>
      <div class="card-body p-6">
        <div class="grid xl:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-5">
          <!-- Use the correct color and style as previously preferred -->
          <div class="price-table bg-opacity-[0.16] dark:bg-opacity-[0.36] rounded-[6px] p-6 text-slate-900 dark:text-white relative overflow-hidden z-[1] bg-primary-500">
            <div class="overlay absolute right-0 top-0 w-full h-full z-[-1]">
              <img src="{$_theme}/assets/images/all-img/big-shap3.png" alt="" class="ml-auto block">
            </div>
 
            <header class="mb-6">
              <h4 class="text-xl mb-5">{$active_plan.name_plan}</h4>
              <div class="space-x-4 relative flex items-center mb-5 rtl:space-x-reverse">
                <span class="text-[32px] leading-10 font-medium"> {Lang::moneyFormat($active_plan.price)} </span>
                <!-- <span class="text-xs text-primary-500 font-medium px-3 py-1 rounded-full inline-block bg-white uppercase h-auto">Save 20%</span> -->
              </div>
              <p class="text-slate-500 dark:text-slate-300 text-sm"> {Lang::T('Validity')} : {$active_plan.validity} {$active_plan.validity_unit} </p>
            </header>
 
            <div class="price-body space-y-8">
              <p class="text-sm leading-5 text-slate-600 dark:text-slate-300">
              <table class="table table-bordered table-striped">
                <tbody>
                  <tr>
                    <td>Service Type:&nbsp; </td>
                    <td>{$active_plan.type}</td>
                  </tr>
                  <!-- <tr>
                    <td>Include:&nbsp; </td>
                    <td> 24/7 Support</td>
                  </tr> -->
                 <!--  <tr>
                    <td>Include:&nbsp; </td>
                    <td>Speed Burst</td>
                  </tr> -->
                </tbody>
              </table>
              </p>
              <div>
                <a href="{$_url}order/buy/{$router_id|default:''}/{$active_plan.id}" onclick="return confirm('{Lang::T('Buy this? your active package will be overwritten')}')">
                  <button class="btn-outline-dark dark:border-slate-400 w-full btn"> Pay Now</button>
                </a>
              </div> 
              {if $_c['enable_balance'] == 'yes' && $_user['balance'] >= $active_plan.price}
              <div>
                <a href="{$_url}order/pay/{$router_id|default:''}/{$active_plan.id}" onclick="return confirm('{Lang::T('Pay this with Balance? your active package will be overwritten')}')">
                  <button class="btn-outline-dark dark:border-slate-400 w-full btn"> {Lang::T('Pay With Balance')}</button>
                </a>
              </div>
              {/if}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
{else}
  <p>No active plan found.</p>
{/if}
 
{include file="sections/user-footer.tpl"}