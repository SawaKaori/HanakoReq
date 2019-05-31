<template>
    <div>
        <el-row>
            <el-col>
            </el-col>
        </el-row>
        <el-table
            v-loading="loading"
            :default-sort="{prop: 'id', order: 'descending'}"
            :cell-class-name="cellClassName"
            :data="comments"> <!-- ←」これだろ！　-->
            <el-table-column
                sortable
                prop="sender"
                label="登録日"
                width="100px">
            </el-table-column>
            <el-table-column
                sortable
                prop="sender"
                label="依頼者"
                width="80px"></el-table-column>
            <el-table-column
                sortable
                prop="comment"
                label="内容">
                <template slot-scope="scope">
                    <span v-html="commentToHtml(scope.row.commnet)"></span>
                    <el-button type="primary"
                        size="mini"
                        @click="editComment(scope.row.id)">
                        コメント登録
                    </el-button>
                </template>
                </el-table-column>
                  </el-table>
    </div>
    
</template>
    
<script>
import Defines from '~/components/Defines.vue';

export default {
    props: [
        'loading',
        'buttonType',
        'records'
    ],
    mixins: [
        Defines
    ],
    methods: {
    
    }
}
</script>


