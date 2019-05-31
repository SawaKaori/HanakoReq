<!--
/**
 * EditRequestDialog.vue
 * 依頼内容の登録・修正ダイアログ
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.16 J.Kawahara 新規作成
 */
-->
<template>
    <el-dialog
        title="依頼内容の編集"
        :visible.sync="dialogVisible">

        <el-form ref="editRequestForm"
            size="mini"
            :model="record"
            label-width="120px">

            <el-row v-if="record.id > 0">
                <el-col :span="24">
                    <el-form-item label="削除">
                        <el-button
                            @click="removeRecord()"
                            type="danger">この依頼を削除する</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item label="登録日" prop="registed_dt">
                        <el-date-picker
                            v-model="record.registed_dt"
                            type="date"
                            format="yyyy年MM月dd日"
                            value-format="yyyy-MM-dd"
                            placeholder="登録日"></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item prop="hurry_flag">
                        <el-checkbox v-model="isHurry" border>急ぎの案件</el-checkbox>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="12">
                    <el-form-item label="期限" prop="limit_dt">
                        <el-date-picker
                            v-model="record.limit_dt"
                            type="date"
                            format="yyyy年MM月dd日"
                            value-format="yyyy-MM-dd"
                            placeholder="期限"></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="24">
                    <el-form-item label="依頼者" prop="sender">
                        <el-input v-model="record.sender"
                            placeholder="依頼者"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="24">
                    <el-form-item label="内容" prop="senders_comment">
                        <el-input type="textarea" v-model="record.senders_comment"
                            rows="8"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row v-if="adminMode">
                <el-col :span="24">
                    <el-form-item label="依頼区分" prop="request_type">
                        <el-select v-model="record.request_type">
                            <el-option
                                v-for="item in s_requestType"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                        </el-select>
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
        'adminMode'
    ],
    data() {
        return {
            dialogVisible: false,
            record: {},
        };
    },
    computed: {
        isHurry: {
            get() { return (this.record.hurry_flag == 1); },
            set(val) { this.record.hurry_flag = val ? 1 : 0; }
        },
        s_requestType() {
            return [{ id: -1, name: ' ' }].concat(this.RequestTypeList);
        }
    },
    methods: {
        open(record) {
            this.record = Object.assign({}, record);
            this.dialogVisible = true;
        },
        close() {
            this.dialogVisible = false;
        },
        submit() {
            this.$emit('submit', this.record);
        },
        removeRecord() {
            this.$confirm('この依頼を削除します。よろしいですか？', '削除の確認', {
                confirmButtonText: '削除します',
                cancelButtonText: 'キャンセル',
                type: 'warning'
            })
            .then(() => {
                this.$emit('remove', this.record);
            });
        }
    }
}
</script>