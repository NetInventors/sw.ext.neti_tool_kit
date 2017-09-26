{extends file="parent:frontend/detail/tabs/description.tpl"}

{block name='frontend_detail_description_properties'}
    {if $sArticle.sProperties}
        <div class="product--properties panel has--border">
            <table class="product--properties-table">
                {foreach $sArticle.sProperties as $sProperty}
                    {if isset($sProperty.attributes.core) && '1' === $sProperty.attributes.core->get('neti_tool_kit_hide_in_frontend')}
                        {continue}
                    {/if}
                    <tr class="product--properties-row">
                        {* Property label *}
                        {block name='frontend_detail_description_properties_label'}
                            <td class="product--properties-label is--bold">{$sProperty.name|escape}:</td>
                        {/block}

                        {* Property content *}
                        {block name='frontend_detail_description_properties_content'}
                            <td class="product--properties-value">{$sProperty.value|escape}</td>
                        {/block}
                    </tr>
                {/foreach}
            </table>
        </div>
    {/if}
{/block}