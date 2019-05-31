<!--
/**
 * EditStatusDialog.vue
 * 状態変更のダイアログ
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.17 J.Kawahara 新規作成
 */
-->
<template>
    <el-dialog
        title="状態の編集"
        :visible.sync="dialogVisible">

        <el-form ref="editStatusForm"
            size="mini"
            :model="record"
            label-width="120px">

            <el-row>
                <el-col :span="24">
                    <el-form-item label="担当者" prop="worker">
                        <el-select v-model="record.worker">
                            <el-option
                                v-for="item in WorkerList"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="24">
                    <el-form-item label="サブ担当者" prop="worker">
                        <el-select v-model="record.worker_sub">
                            <el-option
                                v-for="item in WorkerList"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>            <el-row>
                <el-col :sapn="24">
                    <el-form-item label="状態" prop="status">
                        <el-select v-model="record.status">
                            <el-option
                                v-for="item in StatusList"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="24">
                    <el-form-item label="完了日" prop="complete_dt">
                        <el-date-picker
                            v-model="record.complete_dt"
                            type="date"
                            format="yyyy年MM月dd日"
                            value-format="yyyy-MM-dd"
                            placeholder="完了日"></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row>
                <el-col :span="24">
                    <el-form-item label="コメント" prop="workers_comment">
                        <el-input type="textarea" rows="8" v-model="record.workers_comment"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            
        </el-form>
        <span slot="footer" class="dialog-footer">
            <el-button type="info"
                @click="close()">閉じる</el-button>
            <el-button type="success"
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
    data() {
        return {
            dialogVisible: false,
            record: {},
        };
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
        }
    }
}
</script>