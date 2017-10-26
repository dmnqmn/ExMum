export class Gallery {
    constructor({ id, name, description, secret, ownerId, uid }) {
        this.id = id
        this.name = name
        this.description = description
        this.secret = secret
        this.ownerId = ownerId || uid
    }
}
