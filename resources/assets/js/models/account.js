import defaultAvatar from '@/image/exmum.svg'
import assign from 'object-assign'

export class User {
    constructor({ id, username, gender, description, avatar }, addition) {
        const props = ['id', 'username', 'gender', 'description', 'avatar']

        this.id = id
        this.username = username
        this.gender = gender
        this.description = description
        this.avatar = avatar || defaultAvatar

        if (addition) {
            this.merge(addition)
        }
    }

    merge(info) {
        const props = ['username', 'gender', 'description', 'avatar']

        for (let prop in info) {
            if (props.indexOf(prop) !== -1 && info[prop]) {
                this[prop] = info[prop]
            }
        }
    }
}
