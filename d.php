<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>线上模拟做蛋包饭 · オムライスキッチン</title>
<style type="text/css">
/* 沿用拍手板的风格：淡蓝背景，白色卡片，柔和圆角，表单元素风格 */
body {
    background-color: #b0d4ff;
    margin: 0;
    padding: 40px 20px;
    font-family: 'Meiryo', 'MS PGothic', 'Hiragino Kaku Gothic ProN', 'Noto Sans CJK JP', sans-serif;
    font-size: 14px;
    line-height: 1.4;
    color: #222;
}
.white-box {
    width: 800px;
    margin: 0 auto;
    background-color: #ffffff;
    border: 1px solid #7f9db9;
    padding: 25px 30px 30px 30px;
    border-radius: 2rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
.omurice-simulator {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.controls {
    flex: 1.2;
    min-width: 240px;
}
.result-area {
    flex: 1.8;
    min-width: 280px;
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
select, input[type="range"] {
    border: 1px solid #8caccc;
    background-color: #fff;
    padding: 5px;
    font-family: inherit;
    font-size: 13px;
    width: 200px;
    border-radius: 8px;
}
select:focus, input:focus {
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
    border-radius: 30px;
    transition: all 0.2s ease;
    margin-top: 8px;
}
.btn-primary {
    background-color: #2c7a4d;
    border-color: #1e5a3a;
    color: white;
}
.btn-primary:hover {
    background-color: #1e5a3a;
    transform: translateY(-1px);
}
.btn:hover {
    background-color: #d4e2f0;
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
    white-space: pre-wrap;
    border-radius: 12px;
    max-height: 240px;
    overflow-y: auto;
    color: #2e4a6e;
}
.finished-dish {
    background: #fff6e5;
    border: 1px solid #f7d9a0;
    border-radius: 28px;
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
    font-size: 72px;
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
    background: linear-gradient(90deg, #cbdde9, transparent);
}
.footer-note {
    font-size: 11px;
    text-align: center;
    margin-top: 28px;
    color: #7f8c8d;
    border-top: 1px solid #eeeeee;
    padding-top: 12px;
}
</style>
</head>
<body>

<div class="white-box">
    <div class="title-area">
        <h1>🍳 オムライス・シミュレーター 🍛</h1>
        <p>好きな具材と調理法で、あなただけの絶品オムライスを作ろう！</p>
    </div>

    <div class="omurice-simulator">
        <!-- 左侧控制区 -->
        <div class="controls">
            <form id="cookingForm" onsubmit="return false;">
                <table class="form-table">
                    <tr>
                        <th>🥚 玉子の仕上げ：</th>
                        <td>
                            <select id="eggStyle">
                                <option value="とろとろ">とろとろ半熟 (半熟软嫩)</option>
                                <option value="ふわふわ" selected>ふわふわ玉子 (蓬松蛋皮)</option>
                                <option value="しっかり">しっかり焼き (全熟紧实)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>🍚 ライスの味：</th>
                        <td>
                            <select id="riceStyle">
                                <option value="ケチャップライス" selected>ケチャップライス (番茄酱炒饭)</option>
                                <option value="バターライス">バターライス (黄油炒饭)</option>
                                <option value="チキンライス">チキンライス (鸡肉炒饭)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>🥫 かけるソース：</th>
                        <td>
                            <select id="sauceType">
                                <option value="トマトソース" selected>トマトソース (番茄酱)</option>
                                <option value="デミグラスソース">デミグラスソース (多明格拉斯酱)</option>
                                <option value="ホワイトソース">ホワイトソース (奶油白酱)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>✨ 特別トッピング：</th>
                        <td>
                            <select id="topping">
                                <option value="なし">なし (无追加)</option>
                                <option value="チーズ">🧀 チーズ (奶酪)</option>
                                <option value="パセリ">🌿 パセリ (香芹)</option>
                                <option value="トリュフ">✨ トリュフオイル (松露油)</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button type="button" class="btn btn-primary" id="cookBtn">👨‍🍳 調理開始！</button>
                    <button type="button" class="btn reset-btn" id="resetBtn">🔄 リセット</button>
                </div>
            </form>
            <div class="option-group">
                <span class="option-label">🔥 こだわり度</span><br>
                <input type="range" id="passion" min="0" max="100" value="70" style="width: 180px;">
                <span id="passionValue" style="margin-left: 8px;">70%</span>
                <div style="font-size:12px; color:#6f8fae;">情熱レベルが味に影響します</div>
            </div>
        </div>

        <!-- 右侧结果区 -->
        <div class="result-area">
            <div class="cooking-log" id="cookingLog">
                👩‍🍳 まだ調理していません。<br>
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
        💡 こだわり度と選択によって仕上がりが変化します。お好みの一皿を追求しよう！
</br>
copyright by mingh (c)2003
</br>
オムライス大好き同盟
    </div>
</div>

<script>
    // 模拟制作蛋包饭的核心逻辑
    const eggMap = {
        "とろとろ": { name: "とろとろ半熟", emoji: "🥚💧", text: "黄金の半熟卵がとろ〜り", effect: "優しい口どけ" },
        "ふわふわ": { name: "ふわふわ玉子", emoji: "🍳✨", text: "ふわっと軽やか玉子", effect: "軽やかな食感" },
        "しっかり": { name: "しっかり焼き", emoji: "🍳🔥", text: "香ばしい薄焼き玉子", effect: "香ばしいコク" }
    };
    const riceMap = {
        "ケチャップライス": { name: "ケチャップライス", emoji: "🍅🍚", text: "甘酸っぱいケチャップ味", effect: "王道の味わい" },
        "バターライス": { name: "バターライス", emoji: "🧈🍚", text: "バターの豊かな香り", effect: "まろやかコク" },
        "チキンライス": { name: "チキンライス", emoji: "🐔🍚", text: "ジューシーなチキン入り", effect: "ボリューム満点" }
    };
    const sauceMap = {
        "トマトソース": { name: "トマトソース", emoji: "🍅", text: "自家製トマトソース", effect: "さっぱり" },
        "デミグラスソース": { name: "デミグラスソース", emoji: "🍖", text: "深みのあるデミグラス", effect: "濃厚コク" },
        "ホワイトソース": { name: "ホワイトソース", emoji: "🥛", text: "クリーミーホワイトソース", effect: "まろやか" }
    };
    const toppingMap = {
        "なし": { name: "シンプル", emoji: "", text: "素材の味を堪能", addEffect: "" },
        "チーズ": { name: "チーズ", emoji: "🧀", text: "とろけるチーズ", addEffect: "コクがアップ" },
        "パセリ": { name: "パセリ", emoji: "🌿", text: "爽やかな彩り", addEffect: "香りが引き立つ" },
        "トリュフ": { name: "トリュフオイル", emoji: "✨", text: "芳醇な香り", addEffect: "極上の贅沢" }
    };

    // 获取DOM元素
    const cookBtn = document.getElementById('cookBtn');
    const resetBtn = document.getElementById('resetBtn');
    const cookingLogDiv = document.getElementById('cookingLog');
    const dishEmojiSpan = document.getElementById('dishEmoji');
    const dishCommentSpan = document.getElementById('dishComment');
    const passionSlider = document.getElementById('passion');
    const passionSpan = document.getElementById('passionValue');
    const eggSelect = document.getElementById('eggStyle');
    const riceSelect = document.getElementById('riceStyle');
    const sauceSelect = document.getElementById('sauceType');
    const toppingSelect = document.getElementById('topping');

    // 更新热情值显示
    passionSlider.addEventListener('input', function() {
        passionSpan.innerText = this.value + '%';
    });

    // 重置模拟器（清空日志，重置成品默认图）
    function resetSimulator() {
        cookingLogDiv.innerHTML = "👩‍🍳 調理ログをリセットしました。<br>新しい材料を選んで「調理開始」！";
        dishEmojiSpan.innerHTML = "🍳 ➡ 🍛";
        dishCommentSpan.innerHTML = "調理すると、ここに絶品オムライスが表示されます";
        // 可选恢复表单默认值 (不重置下拉框，保留当前选择，但可以根据需要保留)
        // 为了体验，不清空选择，仅清空结果
    }

    // 模拟异步烹饪过程（逐步显示步骤）
    async function simulateCooking(ingredients, passionLevel) {
        const logLines = [];
        const egg = ingredients.egg;
        const rice = ingredients.rice;
        const sauce = ingredients.sauce;
        const topping = ingredients.topping;

        // 步骤1
        logLines.push("🔪 材料を準備中...");
        logLines.push(`🍳 フライパンを熱して、${egg.text} を準備。`);
        await delay(400);
        logLines.push(`🍚 ${rice.text} を炒めて香りを引き出します。`);
        await delay(500);
        logLines.push(`🥚 ふわふわ卵でライスを包みます...`);
        await delay(600);
        logLines.push(`🍛 お皿にそっと盛り付け、${sauce.text} をかけます。`);
        if (topping.name !== "シンプル") {
            logLines.push(`✨ トッピング: ${topping.text} をプラス！`);
        }
        await delay(300);
        logLines.push(`🔥 こだわり度 ${passionLevel}% の情熱を注入中...`);

        // 根据热情值影响最终评价
        let passionBonus = "";
        if (passionLevel >= 80) passionBonus = "溢れ出る愛情が味に深みを与えました！";
        else if (passionLevel >= 50) passionBonus = "ほどよい熱意が美味しさを引き出しています。";
        else passionBonus = "もう少し情熱を込めるとさらに美味しくなるかも…";

        logLines.push(`✨ 調理完了！ ${passionBonus}`);
        return logLines;
    }

    // 根据选择生成成品描述和emoji组合
    function generateResult(ingredients, passionLevel) {
        const egg = ingredients.egg;
        const rice = ingredients.rice;
        const sauce = ingredients.sauce;
        const topping = ingredients.topping;
        let baseEmoji = "🍳✨";
        let comment = "";

        // 综合判定等级
        let score = 50;
        if (egg.name === "ふわふわ玉子") score += 15;
        if (rice.name === "ケチャップライス") score += 10;
        if (sauce.name === "デミグラスソース") score += 12;
        if (topping.name !== "シンプル") score += 8;
        score += Math.floor(passionLevel / 10); // 热情加成

        let tasteLevel = "";
        if (score >= 85) tasteLevel = "至高の一品！ ★★★★★";
        else if (score >= 70) tasteLevel = "絶妙なバランス ★★★★☆";
        else if (score >= 55) tasteLevel = "なかなかの美味しさ ★★★☆☆";
        else tasteLevel = "普通ですが、家庭的な味わい ★★☆☆☆";

        // 根据不同组合调整emoji
        let finalEmoji = "";
        if (sauce.name === "トマトソース") finalEmoji = "🍅🍛";
        else if (sauce.name === "デミグラスソース") finalEmoji = "🍖🍛";
        else finalEmoji = "🥛🍛";
        if (topping.emoji) finalEmoji = topping.emoji + finalEmoji;
        finalEmoji = "🍳+" + finalEmoji;

        // 特别风味描述
        let flavorNote = "";
        if (egg.name === "とろとろ半熟" && rice.name === "バターライス") flavorNote = "とろける卵とバターのハーモニーが口いっぱいに広がります。";
        else if (sauce.name === "デミグラスソース" && topping.name === "トリュフ") flavorNote = "高級レストランの味わい、芳醇な香りが贅沢なひとときを演出。";
        else if (rice.name === "チキンライス" && sauce.name === "ホワイトソース") flavorNote = "クリーミーなソースがチキンの旨みを包み込み、優しいコク。";
        else flavorNote = `${egg.text} と ${rice.text}、${sauce.text} のバランスが心地よい一皿です。`;

        comment = `【${tasteLevel}】 ${flavorNote} ${topping.addEffect ? "＋" + topping.addEffect : ""}`;
        if (passionLevel >= 90) comment += " 愛情たっぷり、心まで温まる特製オムライス！";
        else if (passionLevel < 30) comment += " もう少し情熱を込めて作ると、もっと美味しくなりますよ。";

        return { emoji: finalEmoji, comment: comment };
    }

    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // 主烹饪流程
    async function startCooking() {
        // 锁定按钮避免重复点击
        cookBtn.disabled = true;
        cookBtn.textContent = "⏳ 調理中...";
        resetBtn.disabled = true;

        // 获取当前选中的值
        const eggValue = eggSelect.value;
        const riceValue = riceSelect.value;
        const sauceValue = sauceSelect.value;
        const toppingValue = toppingSelect.value;
        const passionLevel = parseInt(passionSlider.value, 10);

        const ingredients = {
            egg: eggMap[eggValue],
            rice: riceMap[riceValue],
            sauce: sauceMap[sauceValue],
            topping: toppingMap[toppingValue]
        };

        // 清空日志区域并开始模拟
        cookingLogDiv.innerHTML = "👨‍🍳 調理スタート！<br>";
        const logLines = await simulateCooking(ingredients, passionLevel);
        // 逐步显示日志（动画效果）
        for (let line of logLines) {
            cookingLogDiv.innerHTML += line + "<br>";
            cookingLogDiv.scrollTop = cookingLogDiv.scrollHeight;
            await delay(350);
        }

        // 生成最终成品
        const final = generateResult(ingredients, passionLevel);
        dishEmojiSpan.innerHTML = final.emoji;
        dishCommentSpan.innerHTML = final.comment;

        // 额外增加一点个性化味道文字
        cookingLogDiv.innerHTML += `<br>🎉 完成！ 「${ingredients.egg.name} × ${ingredients.rice.name} × ${ingredients.sauce.name} ${ingredients.topping.name !== "シンプル" ? " + " + ingredients.topping.name : ""}」<br>`;
        cookingLogDiv.scrollTop = cookingLogDiv.scrollHeight;

        // 恢复按钮
        cookBtn.disabled = false;
        cookBtn.textContent = "👨‍🍳 調理開始！";
        resetBtn.disabled = false;
    }

    // 重置模拟器，同时重置显示内容
    function handleReset() {
        resetSimulator();
        // 可重新设定热情条默认，但不需要重置下拉框（保留用户偏好）
        // 如果希望完全回到初始状态，可重置表单选择器到默认值（可选）
        // 这里简单只清除烹饪日志和成品显示
        cookingLogDiv.innerHTML = "👩‍🍳 調理ログをリセットしました。<br>新しい材料を選んで「調理開始」！";
        dishEmojiSpan.innerHTML = "🍳 ➡ 🍛";
        dishCommentSpan.innerHTML = "調理すると、ここに絶品オムライスが表示されます";
        // 但热情滑块维持原值，无妨
    }

    // 绑定事件
    cookBtn.addEventListener('click', startCooking);
    resetBtn.addEventListener('click', handleReset);
</script>
</body>
</html>












