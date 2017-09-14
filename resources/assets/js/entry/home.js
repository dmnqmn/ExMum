import '@css/entry/home'

import $ from 'jquery'
import 'bootstrap'

import '@js/common/header'

import masonry from 'masonry-layout'
import imagesLoaded from 'imagesloaded'

$(() => {
    let photoMasonry = new masonry('#photo-masonry', {
        columnWidth: '#photo-sizer',
        itemSelector: '.photo-wrapper',
        gutter: 8
    })

    imagesLoaded('#photo-masonry').on('progress', () => {
        photoMasonry.layout()
    })
})
