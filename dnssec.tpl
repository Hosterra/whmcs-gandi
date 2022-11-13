<div class="section">
    <div class="section-header">
        <h2 class="section-title">{$LANG.gandi.dnssec.title}</h2>
        <p class="section-desc">{$desc}</p>
    </div>
    {if $addKey}
        <div class="section-body">
            <p class="section-desc">{$LANG.gandi.dnssec.activation}</p>
            <form method="post" action="clientarea.php?action=domaindetails">
                <input type="hidden" name="domainid" value="{$domainid}" />
                <input type="hidden" name="modop" value="custom" />
                <input type="hidden" name="a" value="Dnssec" />
                <input type="submit" name="addKey" value="{$LANG.gandi.dnssec.enable}"/>
            </form>
        </div>
    {/if}
    {if $rmKey}
        <div class="section-body">
            {foreach from=$keys item=key}
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">{$LANG.gandi.dnssec.key} #{$key.id}</h3></div>
                    <ul class="list-info list-info-50 list-info-bordered">
                        <li><span class="list-info-title">{$key.type.name}</span><span class="list-info-text"><span>{$key.type.value}</span></span></li>
                        <li><span class="list-info-title">{$key.algorithm.name}</span><span class="list-info-text"><span>{$key.algorithm.value}</span></span></li>
                        <li><span class="list-info-title">{$key.digest.name}</span><span class="list-info-text"><span><code>{$key.digest.value}</code></span></span></li>
                        <li><span class="list-info-title">{$key.public.name}</span><span class="list-info-text"><span><code>{$key.public.value}</code></span></span></li>
                        <li><span class="list-info-title">{$key.tag.name}</span><span class="list-info-text"><span>{$key.tag.value}</span></span></li>
                    </ul>
                </div>
            {/foreach}
            <p class="section-desc">{$LANG.gandi.dnssec.deactivation}</p>
            <form method="post" action="clientarea.php?action=domaindetails">
                <input type="hidden" name="domainid" value="{$domainid}" />
                <input type="hidden" name="modop" value="custom" />
                <input type="hidden" name="a" value="Dnssec" />
                <input type="submit" name="rmKey" value="{$LANG.gandi.dnssec.disable}"/>
            </form>
        </div>
    {/if}
</div>