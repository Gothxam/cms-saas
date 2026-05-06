/**
 * MedOS Professional Agora RTC Handler
 * V13 - Reliable Local State (Don't trust SDK enabled state)
 */
class AgoraHandler {
    constructor(appId, channelName, token = null, uid = null, onJoin = null, onReady = null) {
        this.appId = appId;
        this.channel = channelName;
        this.token = token;
        this.uid = uid;
        this.onJoin = onJoin;
        this.onReady = onReady; // Callback for when tracks are published
        this.client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
        this.localTracks = { videoTrack: null, audioTrack: null };
        this.remoteUsers = {};
        
        this.isJoining = false;
        this.isJoined = false;
        this.isAudioEnabled = true;
        this.isVideoEnabled = true;
        this.discoveryInterval = null;
    }

    log(msg, isError = false) {
        console.log(`Agora: ${msg}`);
        const el = document.getElementById("agora-debug");
        if(el) {
            el.innerText = msg;
            el.style.color = isError ? "#f87171" : "#2dd4bf";
        }
    }

    async join() {
        if(this.isJoined || this.isJoining) return;
        this.isJoining = true;
        this.log(`Connecting...`);

        this.client.on("user-published", (user, mediaType) => this.handleUserPublished(user, mediaType));
        this.client.on("user-joined", (user) => {
            this.log(`Peer ${user.uid} Joined`);
            this.remoteUsers[user.uid] = user;
        });
        this.client.on("user-left", (user) => {
            delete this.remoteUsers[user.uid];
            this.log(`Peer Disconnected`);
        });

        try {
            const tokenToUse = (this.token === '' || this.token === null) ? null : this.token;
            await this.client.join(this.appId, this.channel, tokenToUse, this.uid);
            
            this.isJoined = true;
            this.isJoining = false;
            this.log(`Online`);

            try {
                this.localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
                this.localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack({
                    encoderConfig: "480p_1"
                });
                
                // RESTORE LOCAL MIRROR
                if (document.getElementById("local-video")) {
                    this.localTracks.videoTrack.play("local-video");
                }
                
                await this.client.publish(Object.values(this.localTracks));
                
                this.log("Streaming Active");
                if(this.onReady) this.onReady(); // UNLOCK BUTTONS
            } catch (pError) {
                this.log("Hardware Blocked", true);
            }

        } catch (error) {
            this.isJoining = false;
            this.log("Connection Failed", true);
        }
    }

    async handleUserPublished(user, mediaType, retryCount = 0) {
        // DEEP SYNC: Always try to get the freshest user object from the SDK's internal list
        const freshUser = this.client.remoteUsers.find(u => u.uid === user.uid) || user;
        
        try {
            await this.client.subscribe(freshUser, mediaType);
            this.log(`Linked ${mediaType}`);

            if (mediaType === "video") {
                this.remoteUsers[freshUser.uid] = freshUser;
                const remoteDiv = document.getElementById("remote-video");
                if(remoteDiv) {
                    freshUser.videoTrack.play("remote-video");
                    if(this.onJoin) this.onJoin();
                }
            }
            if (mediaType === "audio") {
                freshUser.audioTrack.play();
            }
        } catch (e) {
            console.error("Subscription Error:", e);
            
            // If we've failed, the user might be in a 'joining' state. Retry with lookup.
            if (retryCount < 10) { 
                const delay = (retryCount + 1) * 300;
                this.log(`Syncing... (${retryCount + 1})`);
                setTimeout(() => this.handleUserPublished(user, mediaType, retryCount + 1), delay);
            } else {
                this.log("Connection Unstable", true);
            }
        }
    }

    async leave() {
        if(this.discoveryInterval) clearInterval(this.discoveryInterval);
        this.isJoined = false;
        this.isJoining = false;
        for (let trackName in this.localTracks) {
            var track = this.localTracks[trackName];
            if (track) { track.stop(); track.close(); }
        }
        await this.client.leave();
    }

    async toggleAudio() {
        if (!this.localTracks.audioTrack) {
            this.log("Audio not ready", true);
            return this.isAudioEnabled === false; // correctly returns current isMuted state
        }
        this.isAudioEnabled = !this.isAudioEnabled;
        await this.localTracks.audioTrack.setEnabled(this.isAudioEnabled);
        this.log(this.isAudioEnabled ? "Mic Active" : "Mic Muted");
        console.log("Audio Enabled State:", this.isAudioEnabled);
        return !this.isAudioEnabled; // returns isMuted
    }

    async toggleVideo() {
        if (!this.localTracks.videoTrack) {
            this.log("Camera not ready", true);
            return this.isVideoEnabled === false; // correctly returns current isVideoOff state
        }
        this.isVideoEnabled = !this.isVideoEnabled;
        await this.localTracks.videoTrack.setEnabled(this.isVideoEnabled);
        this.log(this.isVideoEnabled ? "Camera On" : "Camera Off");
        console.log("Video Enabled State:", this.isVideoEnabled);
        return !this.isVideoEnabled; // returns isVideoOff
    }

    async forceSync() {
        this.client.remoteUsers.forEach(user => {
            this.handleUserPublished(user, "video");
            this.handleUserPublished(user, "audio");
        });
    }
}
