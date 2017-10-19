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
        fitWidth: true,
        visibleStyle: { transform: 'translateY(0)', opacity: 1 },
        hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
    })

    let lastUpdateId = window.lastUpdateId;

    imagesLoaded('#photo-masonry').on('progress', () => {
        photoMasonry.layout()
        $('#photo-masonry').show()
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

        $imageProxy.html(photos.map(({ id, url, title, name, description, gallery, author, tags }) =>         `
            <div class="photo-wrapper photo-panel">
                <a href="/photo/${id}">
                    <img class="photo" src="${url}">
                </a>
                <div class="photo-panel-meta">
                    <a class="photo-panel-author" href="${author.url}">${author.user_name}</a>
                    ${
                        gallery ?
                        `
                            <span>发布于</span>
                            <a class="photo-panel-gallery" href="${gallery.url}">${gallery.name}</a>
                        `
                        : ''
                    }
                </div>
                <div class="photo-panel-info">
                    <span class="photo-panel-title">${title}</span>
                    <div class="photo-tag-list">
                        ${
                            tags.map((tag) => `
                                <span class="photo-tag">${tag}</span>
                            `).join('')
                        }
                    </div>
                    <p>${description}</p>
                </div>
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
