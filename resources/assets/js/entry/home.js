import '@css/entry/home'

import $ from 'jquery'
import 'bootstrap'

import '@js/common/header'

import masonry from 'masonry-layout'
import imagesLoaded from 'imagesloaded'
import InfiniteScroll from 'infinite-scroll'

import Vue from 'vue'
import iView from 'iview'
Vue.use(iView)

import assign from 'object-assign'
import axios from 'axios'

import NewPhoto from '../components/NewPhoto'

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

    let lastUpdateId = window.lastUpdateId;

    imagesLoaded('#photo-masonry').on('progress', () => {
        photoMasonry.layout()
    })

    let infScroll = new InfiniteScroll( '#photo-masonry', {
        path: () => {
            let url = `/photos?tags=${escape('Home feed')}&size=8`
            if (lastUpdateId != null) {
                url += `&lastUpdateId=${lastUpdateId}`
            }
            return url
        },
        responseType: 'json',
        outlayer: photoMasonry,
        history: false
    })

    let $imageProxy = $('<div>')
    infScroll.on('load', (response) => {
        if (!response || !response.photos) {
            // TODO: 提示没有更多了
            return;
        }

        let photos = response.photos
        lastUpdateId = response.lastUpdateId
        $imageProxy.html(photos.map(({ id, url, name, description }) => `
            <div class="photo-wrapper">
                <a href="/photo/${id}">
                    <img class="photo" src="${url}">
                    <h5>${name ? name : ''}</h5>
                    <p>${description ? description : ''}</p>
                </a>
            </div>
        `).join('\n'))
        let items = $imageProxy.children('.photo-wrapper').get()
        imagesLoaded(items, () => {
            infScroll.appendItems(items)
            photoMasonry.appended(items)
        });
    })

    new Vue({
        el: '.new-photo-container',

        components: {
            NewPhoto
        }
    })
})
