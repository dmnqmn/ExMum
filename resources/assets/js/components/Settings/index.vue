<template>
<Row class="settings">
    <Col :lg="{ span: 16, push: 4 }" :md="{ span: 20, push: 2 }">
        <Card dis-hover>
            <Tabs>
                <TabPane label="个人设置">
                    <UserSettings></UserSettings>
                </TabPane>
            </Tabs>
        </Card>
    </Col>
</Row>
</template>

<script>
import UserSettings from './UserSettings'
import axios from 'axios'

export default {
    components: {
        UserSettings
    }
}
</script>

<style lang="less" scoped>
.settings {
    margin-top: 40px;
}
</style>
