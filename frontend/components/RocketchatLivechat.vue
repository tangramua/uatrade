<template>
    <div></div>
</template>

<script>
    export default {
        name: 'RocketchatLivechat',

        created(){
            if(!process.browser) return;

            const rocketchatUrl = process.env.rocketchatUrl;
            if(!rocketchatUrl) return;

            (function(w, d, s, u) {
                w.RocketChat = function(c) { w.RocketChat._.push(c) }; w.RocketChat._ = []; w.RocketChat.url = u;
                var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
                j.async = true; j.src = rocketchatUrl+'/packages/rocketchat_livechat/assets/rocketchat-livechat.min.js?_=201702160944';
                h.parentNode.insertBefore(j, h);
            })(window, document, 'script', rocketchatUrl+'/livechat');

        }
    }
</script>
