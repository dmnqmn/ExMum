import defaultAvatar from '@/image/exmum.svg'

export class User {
    constructor({ id, username, gender, description, avatar }) {
        console.log(avatar)
        this.id = id
        this.username = username
        this.gender = gender
        this.description = description
        this.avatar = avatar || defaultAvatar
    }
}
