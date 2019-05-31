<template>
    <el-dialog
        title="コメントの編集"
        :visible.sync="dialogVisible">
        <el-form ref="editcommentForm"
            size="mini"
            label-width="120px">

            <el-row>
                <el-col :span="24">
                    <el-form-item label="登録日" prop="comment_dt">
                        <el-date-picker v-model='startDate'
                            type="date"
                            format="yyyy年MM月dd日"
                            value-format="yyyy-MM-dd"
                            placeholder="登録日"></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="24">
                    <el-form-item label="依頼者" prop="sender">
                        <el-input  v-model='sender'></el-input>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="24">
                    <el-form-item label="コメント" prop="comment">
                        <el-input type="textarea"  rows="8"  v-model='comment'></el-input>
                    </el-form-item>
                </el-col>
            </el-row>

        </el-form>
       <span slot="footer" class="dialog-footer">
            <el-button type="info"
                @click="close()">閉じる</el-button>
            <el-button type="primary"
                @click="submit()">登録</el-button>
        </span> 
     </el-dialog>
</template>

<script>

import Defines from '~/components/Defines.vue';

export default {
    mixins: [
        Defines
    ],
    props: [
        'sender',
        'comment'
    ],
    data() {
        return {
            dialogVisible: false,
         /*   record: {},*/
            startDate: new Date()
        };
    },
    methods: {
        open(record) {
            this.record = record;
            
            /*this.commentlist = record.comments;*/

            this.dialogVisible = true;
        },
        close() {
            this.dialogVisible = false;
        },
        submit() {
            const formData = new FormData();
            formData.append('request_id', this.record.id);
            formData.append('comment', this.comment);
            formData.append('sender', this.sender);
            formData.append('comment_dt', this.comment_dt);
             this.$emit('submit', formData);
        },
        removeRecord() {
            this.$confirm('このコメントを削除します。よろしいですか？', '削除の確認', {
                confirmButtonText: '削除します',
                cancelButtonText: 'キャンセル',
                type: 'warning'
            })
            .then(() => {
                const formData = new FormData();
                formData.append('id', rec.id);
                this.$emit('remove', this.record);
            });
        }
    }
}
</script>