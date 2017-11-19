import axios from 'axios'
import { Photo } from '@js/models/Photo'

export async function getPhotos(lastUpdateId, size, tag) {
    let data = (await axios.get('/photos', {
        params: {
            size,
            tag,
            lastUpdateId
        }
    })).data

    let photos = data.photos.map(photoData => new Photo(photoData))

    return {
        lastUpdateId: data.lastUpdateId,
        photos
    }
}

export async function getPhoto(photoId) {
    let data = (await axios.get(`/photo/${photoId}`)).data

    return new Photo(data)
}
