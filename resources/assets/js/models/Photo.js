import { User } from './User'
import { Gallery } from './Gallery'

export class Photo {
    constructor({ id, title, url, description, tags, author, gallery }) {
        this.id = id
        this.title = title
        this.url = url
        this.description = description
        this.tags = tags

        if (author) {
            if (author instanceof User) {
                this.author = author
            } else {
                this.author = new User(author)
            }
        }

        if (gallery) {
            if (gallery instanceof Gallery) {
                this.gallery = gallery
            } else {
                this.gallery = new Gallery(gallery)
            }
        }
    }
}
