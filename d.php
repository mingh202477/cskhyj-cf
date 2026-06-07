<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>オンラインで卵包飯を模擬して作る · オムライスキッチン</title>
    <style type="text/css">
        /* ~ 淡蓝背景，白色卡片，硬边边框，怀旧感 ~ */
        body {
            background-color: #b0d4ff;
            margin: 0;
            padding: 40px 20px;
            font-family: 'Meiryo', 'MS PGothic', 'Hiragino Kaku Gothic ProN', 'Noto Sans CJK JP', monospace;
            font-size: 14px;
            line-height: 1.4;
            color: #222;
        }

        .white-box {
            width: 800px;
            max-width: 95%;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #7f9db9;
            padding: 25px 30px 30px 30px;
        }

        .title-area {
            border-bottom: 1px solid #cccccc;
            margin-bottom: 20px;
            padding-bottom: 8px;
        }

        .title-area h1 {
            margin: 0;
            font-size: 28px;
            font-weight: normal;
            letter-spacing: 1px;
            color: #336699;
        }

        .title-area p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #666;
        }

        /* IE互換のための float レイアウト構成 */
        .omurice-simulator {
            zoom: 1;
        }

        .omurice-simulator:after {
            content: "";
            display: block;
            clear: both;
        }

        .controls {
            float: left;
            width: 330px;
        }

        .result-area {
            float: right;
            width: 410px;
        }

        /* 注文システム表示エリア */
        .order-box {
            background-color: #f0f7ff;
            border: 1px solid #a8c4e5;
            padding: 12px;
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.5;
        }

        .order-title {
            font-weight: bold;
            color: #2c577c;
            margin-bottom: 4px;
            display: block;
        }

        /* 画面幅が狭い場合の簡易フォールバック */
        @media screen and (max-width: 768px) {

            .controls,
            .result-area {
                float: none;
                width: 100%;
                margin-bottom: 20px;
            }
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .form-table th {
            text-align: right;
            width: 100px;
            padding: 12px 12px 12px 0;
            font-weight: normal;
            vertical-align: top;
            color: #2c577c;
        }

        .form-table td {
            padding: 8px 0;
        }

        select,
        input[type="range"] {
            border: 1px solid #8caccc;
            background-color: #fff;
            padding: 5px;
            font-family: monospace;
            font-size: 13px;
            width: 200px;
        }

        select:focus,
        input:focus {
            background-color: #fef8e7;
            border-color: #6688aa;
            outline: none;
        }

        .btn {
            background-color: #e6eef7;
            border: 1px solid #7f9db9;
            color: #2c577c;
            padding: 6px 20px;
            font-size: 14px;
            font-family: inherit;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #2c7a4d;
            border-color: #1e5a3a;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1e5a3a;
        }

        .btn:hover {
            background-color: #d4e2f0;
            border-color: #5f7e9e;
        }

        .option-group {
            margin: 15px 0 10px;
            padding: 8px 0;
            border-top: 1px dashed #e0e8f0;
        }

        .option-label {
            font-weight: bold;
            color: #336699;
            margin-bottom: 5px;
            display: inline-block;
        }

        .cooking-log {
            background: #f9fbfd;
            border-left: 4px solid #f3b33d;
            padding: 12px 15px;
            margin: 15px 0;
            font-family: monospace;
            font-size: 13px;
            height: 200px;
            overflow-y: auto;
            color: #2e4a6e;
        }

        .finished-dish {
            background: #fff6e5;
            border: 1px solid #f7d9a0;
            padding: 18px;
            text-align: center;
            margin-top: 12px;
        }

        .finished-dish h3 {
            margin: 0 0 12px 0;
            color: #c25d2e;
            font-size: 20px;
        }

        .dish-emoji {
            font-size: 50px;
            line-height: 1.2;
        }

        .dish-comment {
            font-style: italic;
            color: #936e3e;
            margin-top: 12px;
        }

        .reset-btn {
            background-color: #f0e4d0;
            border-color: #c9b28b;
            color: #7a5a3a;
            margin-left: 12px;
        }

        .cooking-status {
            margin: 8px 0;
            font-weight: bold;
            color: #b45f2b;
        }

        hr {
            margin: 18px 0;
            border: none;
            height: 1px;
            background: #cbdde9;
        }

        .footer-note {
            font-size: 11px;
            text-align: center;
            margin-top: 28px;
            color: #7f8c8d;
            border-top: 1px solid #eeeeee;
            padding-top: 12px;
            clear: both;
        }

        a {
            color: #336699;
            text-decoration: none;
        }

        a:hover {
            color: #ffaa66;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="white-box">
        <div class="title-area">
            <h1>オムライス・シミュレーター</h1>
            <p>好きな具材と調理法で、あなただけの絶品オムライスを作ろう！</p>
        </div>

        <div class="omurice-simulator">
            <!-- 左侧控制区 -->
            <div class="controls">
                <!-- 注文チャレンジエリア -->
                <div class="order-box">
                    <span class="order-title">🔔 本日のオーダー（本日の注文）:</span>
                    <div id="orderContent">読み込み中...</div>
                    <button type="button" class="btn" id="newOrderBtn" style="padding: 2px 8px; font-size: 11px; margin-top: 6px; background-color: #fff; border-color: #b0d4ff;">別の注文を受ける</button>
                </div>

                <form id="cookingForm" onsubmit="return false;">
                    <table class="form-table">
                        <tr>
                            <th>玉子の仕上げ：</th>
                            <td>
                                <select id="eggStyle">
                                    <option value="とろとろ">とろとろ半熟 (半熟软嫩)</option>
                                    <option value="ふわふわ" selected>ふわふわ玉子 (蓬松蛋皮)</option>
                                    <option value="しっかり">しっかり焼き (全熟紧实)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>ライスの味：</th>
                            <td>
                                <select id="riceStyle">
                                    <option value="ケチャップライス" selected>ケチャップライス (番茄酱炒饭)</option>
                                    <option value="バターライス">バターライス (黄油炒饭)</option>
                                    <option value="チキンライス">チキンライス (鸡肉炒饭)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>かけるソース：</th>
                            <td>
                                <select id="sauceType">
                                    <option value="トマトソース" selected>トマトソース (番茄酱)</option>
                                    <option value="デミグラスソース">デミグラスソース (多明格拉斯酱)</option>
                                    <option value="ホワイトソース">ホワイトソース (奶油白酱)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>特別トッピング：</th>
                            <td>
                                <select id="topping">
                                    <option value="なし">なし (无追加)</option>
                                    <option value="チーズ">チーズ (奶酪)</option>
                                    <option value="パセリ">パセリ (香芹)</option>
                                    <option value="トリュフ">トリュフオイル (松露油)</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <div style="margin-bottom: 15px;">
                        <button type="button" class="btn btn-primary" id="cookBtn">調理開始！</button>
                        <button type="button" class="btn reset-btn" id="resetBtn">リセット</button>
                    </div>
                </form>
                <div class="option-group">
                    <span class="option-label">こだわり度</span><br>
                    <input type="range" id="passion" min="0" max="100" value="70" style="padding: 0;">
                    <span id="passionValue" style="margin-left: 8px;">70%</span>
                    <div style="font-size:12px; color:#6f8fae;">情熱レベルが味に影響します</div>
                </div>
            </div>

            <!-- 右侧结果区 -->
            <div class="result-area">
                <div class="cooking-log" id="cookingLog">
                    まだ調理していません。<br>
                    材料を選んで「調理開始」ボタンを押してください！
                </div>
                <div class="finished-dish" id="finishedDish">
                    <h3>✨ 完成したオムライス ✨</h3>
                    <div class="dish-emoji" id="dishEmoji">🍳 ➡ 🍛</div>
                    <div class="dish-comment" id="dishComment">調理すると、ここにコメントが表示されます</div>
                </div>
            </div>
        </div>
        <div class="footer-note">
            ※ こだわり度と選択によって仕上がりが変化します。お好みの一皿を追求しよう！
            <br>
            copyright by mingh (c)2003
            <br>
            オムライス大好き同盟
        </div>
    </div>

    <script type="text/javascript">
        // 食材映射表
        var eggMap = {
            "とろとろ": {
                name: "とろとろ半熟",
                emoji: "🥚💧",
                text: "黄金の半熟卵がとろ〜り",
                effect: "優しい口どけ"
            },
            "ふわふわ": {
                name: "ふわふわ玉子",
                emoji: "🍳✨",
                text: "ふわっと軽やか玉子",
                effect: "軽やかな食感"
            },
            "しっかり": {
                name: "しっかり焼き",
                emoji: "🍳🔥",
                text: "香ばしい薄焼き玉子",
                effect: "香ばしいコク"
            }
        };
        var riceMap = {
            "ケチャップライス": {
                name: "ケチャップライス",
                emoji: "🍅🍚",
                text: "甘酸っぱいケチャップ味",
                effect: "王道の味わい"
            },
            "バターライス": {
                name: "バターライス",
                emoji: "🧈🍚",
                text: "バターの豊かな香り",
                effect: "まろやかコク"
            },
            "チキンライス": {
                name: "チキンライス",
                emoji: "🐔🍚",
                text: "ジューシーなチキン入り",
                effect: "ボリューム満点"
            }
        };
        var sauceMap = {
            "トマトソース": {
                name: "トマトソース",
                emoji: "🍅",
                text: "自家製トマトソース",
                effect: "さっぱり"
            },
            "デミグラスソース": {
                name: "デミグラスソース",
                emoji: "🍖",
                text: "深みのあるデミグラス",
                effect: "濃厚コク"
            },
            "ホワイトソース": {
                name: "ホワイトソース",
                emoji: "🥛",
                text: "クリーミーホワイトソース",
                effect: "まろやか"
            }
        };
        var toppingMap = {
            "なし": {
                name: "シンプル",
                emoji: "",
                text: "素材の味を堪能",
                addEffect: ""
            },
            "チーズ": {
                name: "チーズ",
                emoji: "🧀",
                text: "とろけるチーズ",
                addEffect: "コクがアップ"
            },
            "パセリ": {
                name: "パセリ",
                emoji: "🌿",
                text: "爽やかな彩り",
                addEffect: "香りが引き立つ"
            },
            "トリュフ": {
                name: "トリュフオイル",
                emoji: "✨",
                text: "芳醇な香り",
                addEffect: "極上の贅沢"
            }
        };

        // 注文（オーダー）データ
        var orderList = [{
                egg: "とろとろ",
                rice: "チキンライス",
                sauce: "デミグラスソース",
                desc: "「濃厚でリッチな、洋食屋さんの本格オムライスが食べたいな」"
            },
            {
                egg: "しっかり",
                rice: "ケチャップライス",
                sauce: "トマトソース",
                desc: "「昔ながらの、ちょっと懐かしい喫茶店風のオムライスを頼むよ」"
            },
            {
                egg: "ふわふわ",
                rice: "バターライス",
                sauce: "ホワイトソース",
                desc: "「優しくてクリーミーな、白いオムライスをお願いします」"
            },
            {
                egg: "とろとろ",
                rice: "バターライス",
                sauce: "トマトソース",
                desc: "「さっぱりトマトソースと、とろける卵のハーモニーを楽しみたい！」"
            }
        ];
        var currentOrder = null;

        // ハプニングイベントデータ
        var cookingEvents = [{
                text: "⚡【ハプニング】フライパンの温度が神がかっており、卵が完璧に滑り降りました！",
                scoreEffect: 15
            },
            {
                text: "⚡【ハプニング】お皿に盛り付ける際、形が奇跡的に美しく整いました！",
                scoreEffect: 10
            },
            {
                text: "⚡【ハプニング】塩コショウの手元が狂って、ちょっとだけ多めに入ってしまった！",
                scoreEffect: -5
            },
            {
                text: "⚡【ハプニング】フライパンを振るのに夢中で、具材が少しコンロに飛び散った！",
                scoreEffect: -3
            },
            {
                text: "🍳【ハプニング】特にトラブルもなく、調理はきわめて順調に進んでいます。",
                scoreEffect: 0
            }
        ];

        // DOM 元素
        var cookBtn = document.getElementById('cookBtn');
        var resetBtn = document.getElementById('resetBtn');
        var newOrderBtn = document.getElementById('newOrderBtn');
        var orderContentDiv = document.getElementById('orderContent');
        var cookingLogDiv = document.getElementById('cookingLog');
        var dishEmojiSpan = document.getElementById('dishEmoji');
        var dishCommentSpan = document.getElementById('dishComment');
        var passionSlider = document.getElementById('passion');
        var passionSpan = document.getElementById('passionValue');
        var eggSelect = document.getElementById('eggStyle');
        var riceSelect = document.getElementById('riceStyle');
        var sauceSelect = document.getElementById('sauceType');
        var toppingSelect = document.getElementById('topping');

        // ランダムに注文を生成
        function generateOrder() {
            var randIndex = Math.floor(Math.random() * orderList.length);
            currentOrder = orderList[randIndex];
            orderContentDiv.innerHTML = "<span style='color:#b45f2b; font-weight:bold;'>" + currentOrder.desc + "</span><br>" +
                "<span style='color:#555;'>（希望： " + eggMap[currentOrder.egg].name + " ＋ " +
                riceMap[currentOrder.rice].name + " ＋ " + sauceMap[currentOrder.sauce].name + "）</span>";
        }

        // 更新热情值显示 (IE10/11対応)
        function updatePassionValue() {
            if (passionSpan && passionSlider) {
                passionSpan.innerText = passionSlider.value + '%';
            }
        }
        if (passionSlider) {
            passionSlider.oninput = updatePassionValue;
            passionSlider.onchange = updatePassionValue;
        }

        // 重置模拟器
        function resetSimulator() {
            cookingLogDiv.innerHTML = "調理ログをリセットしました。<br>新しい材料を選んで「調理開始」！";
            dishEmojiSpan.innerHTML = "🍳 ➡ 🍛";
            dishCommentSpan.innerHTML = "調理すると、ここに絶品オムライスが表示されます";
        }

        // 延迟関数
        function delay(ms, callback) {
            setTimeout(callback, ms);
        }

        // 模拟烹饪过程（ハプニングイベントを組み込み）
        function simulateCooking(ingredients, passionLevel, activeEvent, onStep, onComplete) {
            var egg = ingredients.egg;
            var rice = ingredients.rice;
            var sauce = ingredients.sauce;
            var topping = ingredients.topping;
            var steps = [];

            steps.push("材料を準備中...");
            steps.push("フライパンを熱して、" + egg.text + " を準備。");
            steps.push(rice.text + " を炒めて香りを引き出します。");

            // 途中でハプニングイベントを挿入
            steps.push(activeEvent.text);

            steps.push("ふわふわ卵でライスを包みます...");
            steps.push("お皿にそっと盛り付け、" + sauce.text + " をかけます。");
            if (topping.name !== "シンプル") {
                steps.push("トッピング: " + topping.text + " をプラス！");
            }
            steps.push("こだわり度 " + passionLevel + "% の情熱を注入中...");

            var passionBonus = "";
            if (passionLevel >= 80) passionBonus = "溢れ出る愛情が味に深みを与えました！";
            else if (passionLevel >= 50) passionBonus = "ほどよい熱意が美味しさを引き出しています。";
            else passionBonus = "もう少し情熱を込めるとさらに美味しくなるかも…";

            steps.push("調理完了！ " + passionBonus);

            var index = 0;

            function nextStep() {
                if (index < steps.length) {
                    if (onStep) onStep(steps[index]);
                    index++;
                    delay(350, nextStep);
                } else {
                    if (onComplete) onComplete();
                }
            }
            nextStep();
        }

        // 根据选择和事件生成成品描述、称号和emoji组合
        function generateResult(ingredients, passionLevel, orderMatch, eventEffect) {
            var egg = ingredients.egg;
            var rice = ingredients.rice;
            var sauce = ingredients.sauce;
            var topping = ingredients.topping;

            // スコア算出ロジック
            var score = 50;
            if (egg.name === "ふわふわ玉子") score += 15;
            if (rice.name === "ケチャップライス") score += 10;
            if (sauce.name === "デミグラスソース") score += 12;
            if (topping.name !== "シンプル") score += 8;

            // 開発情熱加算
            score += Math.floor(passionLevel / 10);

            // 注文達成ボーナス（+20点）
            if (orderMatch) {
                score += 20;
            }

            // ハプニング補正
            score += eventEffect;

            // 称号システムのマッピング
            var titleName = "";
            var tasteLevel = "";
            if (score >= 105) {
                titleName = "【神シェフ】オムライス界の生ける伝説";
                tasteLevel = "神の領域に達した奇跡のオムライス！ ★★★★★★★";
            } else if (score >= 90) {
                titleName = "【一流シェフ】こだわり洋食屋の若き天才";
                tasteLevel = "至高の一品！誰もが唸るプロの味。 ★★★★★★";
            } else if (score >= 70) {
                titleName = "【一人前職人】街で噂の人気オムライス作家";
                tasteLevel = "絶妙なバランス！大満足間違いなし。 ★★★★☆";
            } else if (score >= 50) {
                titleName = "【見習いシェフ】絶賛フライパン修行中の卵使い";
                tasteLevel = "なかなかの美味しさ。家庭的でほっとする味。 ★★★☆☆";
            } else {
                titleName = "【キッチン初心者】台所の勇敢なチャレンジャー";
                tasteLevel = "発展途上の味わい。次こそ頑張りましょう！ ★★☆☆☆";
            }

            var finalEmoji = "";
            if (sauce.name === "トマトソース") finalEmoji = "🍅🍛";
            else if (sauce.name === "デミグラスソース") finalEmoji = "🍖🍛";
            else finalEmoji = "🥛🍛";
            if (topping.emoji) finalEmoji = topping.emoji + finalEmoji;
            finalEmoji = "🍳+" + finalEmoji;

            var flavorNote = "";
            if (egg.name === "とろとろ半熟" && rice.name === "バターライス") flavorNote = "とろける卵とバターのハーモニーが口いっぱいに広がります。";
            else if (sauce.name === "デミグラスソース" && topping.name === "トリュフ") flavorNote = "高級レストランの味わい、芳醇な香りが贅沢なひとときを演出。";
            else if (rice.name === "チキンライス" && sauce.name === "ホワイトソース") flavorNote = "クリーミーなソースがチキンの旨みを包み込み、優しいコク。";
            else flavorNote = egg.text + " と " + rice.text + "、" + sauce.text + " のバランスが心地よい一皿です。";

            var comment = titleName + "<br><span style='color:#c25d2e; font-weight:bold;'>" + tasteLevel + "</span><br>" + flavorNote + (topping.addEffect ? "＋" + topping.addEffect : "");

            if (orderMatch) {
                comment += "<br><span style='color:#2c7a4d; font-weight:bold;'>🎯 オーダー達成ボーナス獲得！お客さんは大喜びです！</span>";
            }

            return {
                emoji: finalEmoji,
                comment: comment
            };
        }

        // 主烹饪流程
        function startCooking() {
            cookBtn.disabled = true;
            cookBtn.innerHTML = "調理中...";
            resetBtn.disabled = true;
            newOrderBtn.disabled = true;

            var eggValue = eggSelect.value;
            var riceValue = riceSelect.value;
            var sauceValue = sauceSelect.value;
            var toppingValue = toppingSelect.value;
            var passionLevel = parseInt(passionSlider.value, 10);

            var ingredients = {
                egg: eggMap[eggValue],
                rice: riceMap[riceValue],
                sauce: sauceMap[sauceValue],
                topping: toppingMap[toppingValue]
            };

            // 注文が一致しているか判定
            var orderMatch = false;
            if (currentOrder &&
                eggValue === currentOrder.egg &&
                riceValue === currentOrder.rice &&
                sauceValue === currentOrder.sauce) {
                orderMatch = true;
            }

            // ランダムハプニングイベントの抽選
            var eventIndex = Math.floor(Math.random() * cookingEvents.length);
            var activeEvent = cookingEvents[eventIndex];

            cookingLogDiv.innerHTML = "調理スタート！<br>";

            simulateCooking(ingredients, passionLevel, activeEvent,
                function(step) {
                    cookingLogDiv.innerHTML += step + "<br>";
                    cookingLogDiv.scrollTop = cookingLogDiv.scrollHeight;
                },
                function() {
                    // 成品表示
                    var final = generateResult(ingredients, passionLevel, orderMatch, activeEvent.scoreEffect);
                    dishEmojiSpan.innerHTML = final.emoji;
                    dishCommentSpan.innerHTML = final.comment;

                    cookingLogDiv.innerHTML += "<br>完成！ 「" + ingredients.egg.name + " × " + ingredients.rice.name + " × " + ingredients.sauce.name + (ingredients.topping.name !== "シンプル" ? " + " + ingredients.topping.name : "") + "」<br>";
                    cookingLogDiv.scrollTop = cookingLogDiv.scrollHeight;

                    cookBtn.disabled = false;
                    cookBtn.innerHTML = "調理開始！";
                    resetBtn.disabled = false;
                    newOrderBtn.disabled = false;
                }
            );
        }

        function handleReset() {
            resetSimulator();
        }

        // イベントリスナー登録 (IE8互換のプロパティ方式)
        if (cookBtn) {
            cookBtn.onclick = startCooking;
        }
        if (resetBtn) {
            resetBtn.onclick = handleReset;
        }
        if (newOrderBtn) {
            newOrderBtn.onclick = generateOrder;
        }

        // 初期ロード
        window.onload = function() {
            generateOrder();
        };
    </script>
</body>

</html>