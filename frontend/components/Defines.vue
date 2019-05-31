<!--
/**
 * Defines.vue
 * 定数定義
 * Copyright (C) 2019 J.Kawahara
 * 2019.3.17 J.Kawahara 新規作成
 */
-->
<script>
export default {
    data() {
        return {
            /** 依頼区分 */
            RequestTypeList: [
                { id: 0, name: 'Webページ関連' },
                { id: 1, name: '広告関連' },
                { id: 2, name: '制作物作成' },
                { id: 3, name: '花子リニューアル' },
                { id: 4, name: 'ダイレクトリニューアル' },
                { id: 5, name: 'その他' },
            ],
            /** 依頼者 */
            SenderList: [
                '早坂課長',
                '池田',
                '達川',
            ],
            /** 担当者 */
            WorkerList: [
                '壁下',
                '錦織',
                '伊藤',
            ],
            /** 状態 */
            StatusList: [
                { id: 0, name: '未着手' },
                { id: 1, name: '対応中' },
                { id: 2, name: '確認待ち' },
                { id: 3, name: '完了' },
            ],
            /** 確認欄 */
            ConfirmStatusList: [
                { id: 0, name: '未' },
                { id: 1, name: '確認完了' },
            ],
        };
    }
}
</script>
