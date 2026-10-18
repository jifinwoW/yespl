{************************************************************************************
 * StatTileContents.tpl — Clean white KPI tile card content
 * Design: white bg, label top-left, arrow top-right, big count, trend bottom
 *************************************************************************************}
{strip}
<a href="{$TILE.link}" class="stattile-card" title="View {$TILE.label}">
    <div class="stattile-card__top">
        <span class="stattile-card__label">{$TILE.label}</span>
        <span class="stattile-card__arrow">
            <i class="fa fa-arrow-up" style="transform:rotate(45deg);display:inline-block;"></i>
        </span>
    </div>
    <div class="stattile-card__count">{$TILE.count}</div>
    <div class="stattile-card__bottom">
        <span class="stattile-card__trend-icon"><i class="fa {$TILE.icon}"></i></span>
        <span class="stattile-card__trend-text">{$TILE.subtitle}</span>
    </div>
</a>
{/strip}
