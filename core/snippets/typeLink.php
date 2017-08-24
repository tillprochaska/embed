<a class="embed--link__fallback media" href="<?= $url ?>" target="_blank">
    <?php if($data->providerIcon): ?>
        <div class="media__img">
            <img class="embed--link__icon" src="<?= $data->providerIcon ?>" alt="<?= $data->providerName ?>" />
        </div>
    <?php endif; ?>
    <div class="media__body">
        <h2 class="embed--link__heading"><?= $text ?></h2>
        <div class="embed--link__excerpt"><?= str::excerpt($data->description, 150) ?></div>
        <div class="embed--link__meta marginalia"><?= l('plugin.embed.panelfield.provider') ?> <?= $data->providerName ?></div>
    </div>
</a>
