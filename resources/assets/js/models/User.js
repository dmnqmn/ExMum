import defaultAvatar from '@/image/exmum.svg'
import assign from 'object-assign'

export class User {
    constructor({ id, username, gender, description, avatar, user_name }, addition) {
        const props = ['id', 'username', 'gender', 'description', 'avatar']

        this.id = id
        this.gender = gender
        this.description = description
        this.avatar = avatar || defaultAvatar
        this.username = username || user_name

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
