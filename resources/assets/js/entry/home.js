import '@css/entry/home'

import $ from 'jquery'
import 'bootstrap'

import '@js/common/header'

import masonry from 'masonry-layout'
import imagesLoaded from 'imagesloaded'
import InfiniteScroll from 'infinite-scroll'

import axios from 'axios'

$(() => {
    let photoMasonry = new masonry('#photo-masonry', {
        columnWidth: '#photo-sizer',
        itemSelector: '.photo-wrapper',
        gutter: '#photo-gutter',
        horizontalOrder: true,
        fitWidth: true,
        visibleStyle: { transform: 'translateY(0)', opacity: 1 },
        hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
    })

    let lastUpdateId = 0;

    imagesLoaded('#photo-masonry').on('progress', () => {
        photoMasonry.layout()
    })

    let infScroll = new InfiniteScroll( '#photo-masonry', {
        path: () => `/photos?tags=${escape('Home feed')}&size=8&lastUpdateId=${lastUpdateId}`,
        responseType: 'json',
        outlayer: photoMasonry,
        history: false
    })

    let $imageProxy = $('<div>')
    infScroll.on('load', (response) => {
        if (!response || !response.photos) {
            this.$Message.info('没有更多了');
        }

        let photos = response.photos;
        lastUpdateId = response.lastUpdateId;
        $imageProxy.html(photos.map(({ url }) => `
            <div class="photo-wrapper">
                <img class="photo" src="${url}">
                <p>A fantasy picture</p>
            </div>
        `).join('\n'))
        let items = $imageProxy.children('.photo-wrapper').get();
        imagesLoaded(items, () => {
            infScroll.appendItems(items);
            photoMasonry.appended(items);
        });
    })
    infScroll.loadNextPage()
})
