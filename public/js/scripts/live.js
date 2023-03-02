

var client = AgoraRTC.createClient({ mode: "live", codec: "vp8" });
var screenClient = AgoraRTC.createClient({ mode: "live", codec: "vp8" });
var localTracks = {
	videoTrack: null,
	audioTrack: null,
};

var preLocalTrack = {
	videoTrack: null,
	audioTrack: null,
};

var mics = []; // all microphones devices you can use
var cams = []; // all cameras devices you can use
var currentMic; // the microphone you are using
var currentCam; // the camera you are using

let volumeAnimation;

var remoteUsers = {};
// Agora client options
var options = {
	appid: window.Laravel.agora.appId,
	channel: $("#channel-name").val(),
	uid: null,
	token: $("#agora-token").val(),
	role: "host", // host or audience
};

var localTrackState = {
	videoTrackEnabled: true,
	audioTrackEnabled: true,
};

/**
 * Firebase -----------------------------------------------------------------------------------------------
 */
let eventId = $("#event-id").val();
// init firebase
firebase.initializeApp(window.Laravel.firebaseConfig);

// Adding active user
firebase
	.database()
	.ref("eventActiveUsers/" + eventId + "/" + window.Laravel.user.id)
	.push()
	.set({
		id: window.Laravel.user.id,
		user: window.Laravel.user,
		name: window.Laravel.user.name,
		email: window.Laravel.user.email,
	});

$(".toggle-chat").on("click", function () {
	if ($(this).attr("data-val") == "off") {
		$(this).attr("data-val", "on");
		firebase
			.database()
			.ref("eventPublicLiveChatStatus/" + eventId)
			.set("on");
	} else {
		$(this).attr("data-val", "off");
		firebase
			.database()
			.ref("eventPublicLiveChatStatus/" + eventId)
			.set("off");
	}
});
// Chat status

firebase
	.database()
	.ref()
	.child("eventPublicLiveChatStatus")
	.child(eventId)
	.on("value", function (snapshot) {
		if (snapshot.val() == "on") {
			$(".toggle-chat").attr("data-val", "on");
			// $(".active-chat").removeClass("d-none");
			$(".chat-app-form").find('.chat-element').removeClass('d-none');
			$(".chat-app-form").find('.chat-disabled').addClass('d-none');
			$(".toggle-chat").html("Disable Chat");
			$("#chat-box-tab").click();
		} else {
			$(".toggle-chat").attr("data-val", "off");
			// $(".active-chat").addClass("d-none");
			$(".chat-app-form").find('.chat-element').addClass('d-none');
			$(".chat-app-form").find('.chat-disabled').removeClass('d-none');
			$(".toggle-chat").html("Enable Chat");
		}
	});

// Change Live Status
$(".change-live-status").on("click", function () {
	window.activeUser = window.Laravel.user.id;
	submitLoader($(".change-live-status"));
	let status = $(this).attr('data-status');
	confirm({
		confirmButtonText: "Yes",
		cancelButtonText: "No",
		text: ""
	}, {
		yes: async function() {
			let isActive = false;
			if(status == "live") {
				isActive = await isActiveUserInStream();
				if(!isActive) {
					Swal.fire({
						title: 'You cannot go Live, There is no active user in the stream.',
					});
					return;
				}
				firebase
					.database()
					.ref("eventLiveStatus/" + eventId)
					.set( status );
			} else {
				firebase
					.database()
					.ref("eventLiveStatus/" + eventId)
					.set( status );
			}
			
		}
	});
	
	submitReset($(".change-live-status"));
});

// Live Event status
firebase
	.database()
	.ref()
	.child("eventLiveStatus")
	.child(eventId)
	.on("value", function (snapshot) {
		if (snapshot.val() == "live") {
			$(".change-live-status").html("End");
			$(".change-live-status").attr("data-status", "end");
			$(".event-notice").find('.alert-body').html("<strong>Status:</status> Event is LIVE for end users.");

			try {
				if (window.activeUser == window.Laravel.user.id) {
					$("#startRecording").trigger('click');
					window.activeUser = undefined;
				}
			} catch(e) { console.warn(e) }
		} else if( snapshot.val() == "end" ) {
			$(".change-live-status").html("Go Live");
			$(".change-live-status").attr("data-status", "live");
			$(".event-notice").find('.alert-body').html("<strong>Status:</status> Event has ended.");

			try {

				if (window.activeUser == window.Laravel.user.id) {
					$("#stopRecording").trigger('click');
					window.activeUser = undefined;
				}

			} catch(e) { console.warn(e) }
		} else {
			$(".change-live-status").html("Go Live");
			$(".change-live-status").attr("data-status", "live");
			$(".event-notice").find('.alert-body').html("<strong>Status:</status> Event is not LIVE yet.");
		}
	});

// Listen Events
firebase
	.database()
	.ref("eventActiveUsers/" + eventId)
	.on("child_added", function (snapshot) {
		let object = snapshot.val();
		for (var userId in object) {
			let user = object[userId];
			$(`.user-${user.id}`)
				.find(".user-status")
				.removeClass("text-danger")
				.addClass("text-success")
				.html("Online");
		}
	});

firebase
	.database()
	.ref("eventActiveUsers/" + eventId)
	.on("child_removed", function (snapshot) {
		let object = snapshot.val();
		for (var userId in object) {
			let user = object[userId];
			$(`.user-${user.id}`)
				.find(".user-status")
				.removeClass("text-success")
				.addClass("text-danger")
				.html("Offline");
		}
	});

firebase
	.database()
	.ref("eventActiveUsers/" + eventId + "/" + window.Laravel.user.id)
	.onDisconnect()
	.remove();

firebase
	.database()
	.ref("eventPublicMessage/" + eventId)
	.on("child_added", function (snapshot) {
		createMessageHtml(snapshot);
		return;
	});

async function isActiveUserInStream() {
	let isActive = false;
	firebase
		.database()
		.ref("eventActiveStreamUser/" + eventId)
		.once('value', function(snapshot) {
			let userId = snapshot.val();
			userId = Number(userId);
			isActive = userId > 0
		});

	return isActive;
}
// Add message to chat - function call on form submit
function enterChat(source) {
	var message = $(".message").val();
	if (/\S/.test(message)) {
		let messageObj = {};
		messageObj = {
			user_id: window.Laravel.user.id,
			user: window.Laravel.user,
			message: message,
			created_at: Date.now(),
		};
		firebase
			.database()
			.ref("eventPublicMessage/" + eventId)
			.push()
			.set(messageObj);
	}
}

function createMessageHtml(snapshot) {
	let object = snapshot.val();
	let profileImageUrl = url("images/avatars/profile.png");
	if (object.user.profile_photo_path) {
		profileImageUrl = url(
			`uploads/profile_pic/web/${object.user.profile_photo_path}`
		);
	}
	let chatClass = "";
	if (window.Laravel.user.id != object.user.id) {
		chatClass = "chat-left";
	}
	let chatHtml = `
      <div class="chat ${chatClass}">
        <div class="chat-avatar">
          <span class="avatar box-shadow-1 cursor-pointer">
            <img src="${profileImageUrl}" alt="avatar" height="36" width="36">
          </span>
        </div>
        <div class="chat-body">
          <div class="chat-content">
            <p>${object.message}</p>
          </div>
      </div>
  `;

	$(".chat-message-box").append(chatHtml);
	$(".message").val("");
	$(".user-chats").scrollTop($(".user-chats > .chats").height());
}

/**
 * Agora -------------------------------------------------------------------------
 */
// create Agora client

async function isUserOnline(userId) {
	let online = false;
	firebase
	.database()
	.ref("eventActiveUsers/" + eventId).once('value', function(data) {

		
		let activeUsers = data.val();
		
		for(activeUser in activeUsers) {
			if(activeUser == userId) {
				online = true;
			}
		}
	});

	return online;
}


$(".enter-stream").on("click", async function (e) {
	let userId = $(this).data("user");

	let canAdd = await isUserOnline(userId);
	if(!canAdd) {
		Swal.fire({
			title: 'User is offline!',
		});
		return;
	} 

	showMessageBox(`
		<h4>Please wait...</h4>
	`);

	firebase
		.database()
		.ref("eventActiveStreamUser/" + eventId)
		.set(userId);

});

firebase
	.database()
	.ref()
	.child("eventActiveStreamUser")
	.child(eventId)
	.on("value", async function (snapshot) {
		$(".you-live").addClass('d-none');
		let isOnline = await isUserOnline( snapshot.val() );
		if( isOnline ) {
			options.appid = window.Laravel.agora.appId;
			options.channel = $("#channel-name").val();
			options.token = $("#agora-token").val();
			$(".enter-stream").html("Add to Stream").removeAttr("disabled");
			$(`button[data-user='${snapshot.val()}']`)
				.html("Active")
				.attr("disabled", "disabled");
				$(".stream-controls").addClass("d-none");
				$(".live-progress-bar").addClass('d-none');
				$(".test-live").removeClass('d-none');
			if (snapshot.val() == window.Laravel.user.id) {
				$(".you-live").removeClass('d-none');
				// Play local video
				options.role = "host";
				$(".stream-controls").removeClass("d-none");
				$(".live-progress-bar").removeClass('d-none');
				$(".test-live").addClass('d-none');
			} else {
				// subscribe to the channel
				options.role = "audience";
			}
			await join();
		} 
	});

$(".toggle-camera").on("click", async function () {
	if ($(this).attr("data-icon") == "camera-off") {
		$(this).find("svg").remove();
		$(this).attr("data-icon", "camera");
		$(this).html("<i data-feather='camera'></i>");
		await unmuteVideo();
	} else {
		$(this).find("svg").remove();
		$(this).attr("data-icon", "camera-off");
		$(this).html("<i data-feather='camera-off'></i>");
		await muteVideo();
	}
});

$(".toggle-mic").on("click", async function () {
	if ($(this).attr("data-icon") == "mic-off") {
		$(this).find("svg").remove();
		$(this).attr("data-icon", "mic");
		$(this).html("<i data-feather='mic'></i>");
		await unmuteAudio();
	} else {
		$(this).find("svg").remove();
		$(this).attr("data-icon", "mic-off");
		$(this).html("<i data-feather='mic-off'></i>");
		await muteAudio();
	}
});

$("#screen-share-btn").on("click", async function () {
	client.unpublish();
	for (trackName in localTracks) {
		var track = localTracks[trackName];
		if (track) {
			track.stop();
			track.close();
			localTracks[trackName] = undefined;
		}
	}

	localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
	localTracks.videoTrack = await AgoraRTC.createScreenVideoTrack();

	

	// play local video track
	$("#local-player").find(".message-box").addClass('d-none');
	$("#local-player").html("");
	localTracks.videoTrack.play("local-player");
	$("#local-player-name").text(`localTrack(${options.uid})`);
	// publish local tracks to channel
	await client.publish(Object.values(localTracks));

	$(this).addClass("d-none");
	$("#camera-btn").removeClass("d-none");

	try {
		await localTracks.audioTrack.setEnabled(localTrackState.audioTrackEnabled);
	} catch(e) { console.warn(e) }

	$(".toggle-camera").find("svg").remove();
	$(".toggle-camera").attr("data-icon", "camera");
	$(".toggle-camera").html("<i data-feather='camera'></i>");

	
});

$("#camera-btn").on("click", async function () {
	client.unpublish();
	for (trackName in localTracks) {
		var track = localTracks[trackName];
		if (track) {
			track.stop();
			track.close();
			localTracks[trackName] = undefined;
		}
	}

	(localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack({
		microphoneId: currentMic.deviceId,
	})),
		(localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack({
			cameraId: currentCam.deviceId,
		}));

	// play local video track
	$("#local-player").html("");
	localTracks.videoTrack.play("local-player");
	$("#local-player-name").text(`localTrack(${options.uid})`);
	// publish local tracks to channel
	await client.publish(Object.values(localTracks));
	$(this).addClass("d-none");
	$("#screen-share-btn").removeClass("d-none");

	try {
		await localTracks.audioTrack.setEnabled(localTrackState.audioTrackEnabled);
	} catch(e) { console.warn(e) }
	
	$(".toggle-camera").find("svg").remove();
	$(".toggle-camera").attr("data-icon", "camera");
	$(".toggle-camera").html("<i data-feather='camera'></i>");

});

$(".cam-list").delegate("a", "click", function (e) {
	switchCamera(this.text);
});
$(".mic-list").delegate("a", "click", function (e) {
	switchMicrophone(this.text);
});

$(document).ready(async function () {
	setDevices();
	hotPlugging();
	$(".test-live").on("click", async function () {
		await mediaDeviceTest();
		volumeAnimation = requestAnimationFrame(setVolumeWave);
	});
	$("#media-device-test").on("hidden.bs.modal", function (e) {
		cancelAnimationFrame(volumeAnimation);
		for (trackName in localTracks) {
			var track = localTracks[trackName];
			if (track) {
				track.stop();
				track.close();
				localTracks[trackName] = undefined;
			}
		}
	});
	// await initStream();
});

// the demo can auto join channel with params in url
$(() => {
	var urlParams = new URL(location.href).searchParams;
	options.appid = urlParams.get("appid");
	options.channel = urlParams.get("channel");
	options.token = urlParams.get("token");
	if (options.appid && options.channel) {
		$("#appid").val(options.appid);
		$("#token").val(options.token);
		$("#channel").val(options.channel);
		$("#join-form").submit();
	}
});

$("#host-join").click(function (e) {
	options.role = "host";
});

$("#audience-join").click(function (e) {
	options.role = "audience";
});

$("#join-form").submit(async function (e) {
	e.preventDefault();
	$("#host-join").attr("disabled", true);
	$("#audience-join").attr("disabled", true);
	try {
		options.appid = $("#appid").val();
		options.token = $("#token").val();
		options.channel = $("#channel").val();
		await join();
		if (options.role === "host") {
			$("#success-alert a").attr(
				"href",
				`index.html?appid=${options.appid}&channel=${options.channel}&token=${options.token}`
			);
			if (options.token) {
				$("#success-alert-with-token").css("display", "block");
			} else {
				$("#success-alert a").attr(
					"href",
					`index.html?appid=${options.appid}&channel=${options.channel}&token=${options.token}`
				);
				$("#success-alert").css("display", "block");
			}
		}
	} catch (error) {
		console.error(error);
	} finally {
		$("#leave").attr("disabled", false);
	}
});

$("#leave").click(function (e) {
	leave();
});

async function initStream() {
	try {
		// options.appid = $("#appid").val();
		// options.token = $("#token").val();
		// options.channel = $("#channel").val();
		await join();
		if (options.role === "host") {
			$("#success-alert a").attr(
				"href",
				`index.html?appid=${options.appid}&channel=${options.channel}&token=${options.token}`
			);
			if (options.token) {
				$("#success-alert-with-token").css("display", "block");
			} else {
				$("#success-alert a").attr(
					"href",
					`index.html?appid=${options.appid}&channel=${options.channel}&token=${options.token}`
				);
				$("#success-alert").css("display", "block");
			}
		}
	} catch (error) {
		console.error(error);
	} finally {
		$("#leave").attr("disabled", false);
	}
}

async function join() {

	leave();
	// create Agora client
	client.setClientRole(options.role);

	if (options.role === "audience") {
		// add event listener to play remote tracks when remote user publishs.
		client.on("user-published", handleUserPublished);
		client.on("user-unpublished", handleUserUnpublished);
	}

	// join the channel
	options.uid = await client.join(
		options.appid,
		options.channel,
		options.token || null
	);

	// await mediaDeviceTest();

	if (options.role === "host") {
		volumeAnimation = requestAnimationFrame(setVolumeWave);
		// create local audio and video tracks
		(localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack({
			microphoneId: currentMic.deviceId,
		})),
			(localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack({
				cameraId: currentCam.deviceId,
			}));

		// play local video track
		$("#local-player").find(".message-box").addClass('d-none');
		localTracks.videoTrack.play("local-player");
		$("#local-player-name").text(`localTrack(${options.uid})`);
		// publish local tracks to channel
		await client.publish(Object.values(localTracks));
		// alert('published');
		console.log("publish success");

		hideMessageBox();
	}
}


function hideMessageBox() {
	$(".video-feed-container").removeClass('shadow-sm');
	$(".video-feed-container").find('.message-box').addClass('d-none').removeClass('d-flex');
}

function showMessageBox(html) {
	$(".video-feed-container").addClass('shadow-sm');
	$(".video-feed-container").find('.message-box').removeClass('d-none').addClass('d-flex');
	$(".video-feed-container").find('.message-box').find('.message-box-text').html(html);
}
async function leave() {
	for (trackName in localTracks) {
		var track = localTracks[trackName];
		if (track) {
			track.stop();
			track.close();
			localTracks[trackName] = undefined;
		}
	}

	// remove remote users and player views
	remoteUsers = {};
	$("#remote-playerlist").html("");

	// leave the channel
	await client.leave();

	$(".message-box").removeClass('d-none');

	$("#local-player-name").text("");
	$("#host-join").attr("disabled", false);
	$("#audience-join").attr("disabled", false);
	$("#leave").attr("disabled", true);
	console.log("client leaves channel success");
}

async function subscribe(user, mediaType) {
	const uid = user.uid;
	// subscribe to a remote user
	await client.subscribe(user, mediaType);
	console.log("subscribe success");
	if (mediaType === "video") {
		const player = $(`
      <div id="player-wrapper-${uid}">
        <p class="player-name">remoteUser(${uid})</p>
        <div id="player-${uid}" class="player"></div>
      </div>
    `);
		$("#local-player").html("");
		// user.videoTrack.play(`player-${uid}`);
		$("#local-player").find(".message-box").addClass('d-none');
		user.videoTrack.play("local-player");
		
	}
	if (mediaType === "audio") {
		user.audioTrack.play();
	}

	
}

function handleUserPublished(user, mediaType) {
	const id = user.uid;
	remoteUsers[id] = user;
	subscribe(user, mediaType);
	hideMessageBox();
}

function handleUserUnpublished(user, mediaType) {
	console.log("Unpublisssssssssssssssssssssssssssssssssssssh", mediaType);
	const id = user.uid;
	delete remoteUsers[id];
	$(`#player-wrapper-${id}`).remove();
	if(mediaType == 'video') {
		showMessageBox(`
			<h4>Host has turned off the Camera.</h4>
		`);
	}

	if(mediaType == 'audio') {

	}
}

async function switchCamera(label) {
	currentCam = cams.find((cam) => cam.label === label);
	$(".cam-input").val(currentCam.label);
	// switch device of local video track.
	await localTracks.videoTrack.setDevice(currentCam.deviceId);
}

async function switchMicrophone(label) {
	currentMic = mics.find((mic) => mic.label === label);
	$(".mic-input").val(currentMic.label);
	// switch device of local audio track.
	await localTracks.audioTrack.setDevice(currentMic.deviceId);
}

// show real-time volume while adjusting device.
function setVolumeWave() {
	try {
		volumeAnimation = requestAnimationFrame(setVolumeWave);
		$(".progress-bar").css(
			"width",
			localTracks.audioTrack.getVolumeLevel() * 100 + "%"
		);
		$(".progress-bar").attr(
			"aria-valuenow",
			localTracks.audioTrack.getVolumeLevel() * 100
		);
	} catch (e) { }
}

async function mediaDeviceTest() {
	// create local tracks
	[localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
		// create local tracks, using microphone and camera
		AgoraRTC.createMicrophoneAudioTrack(),
		AgoraRTC.createCameraVideoTrack(),
	]);

	// play local track on device detect dialog
	$("#pre-local-player").html("");
	localTracks.videoTrack.play("pre-local-player");
}

async function muteAudio() {
	if (!localTracks.audioTrack) return;
	await localTracks.audioTrack.setEnabled(false);
	localTrackState.audioTrackEnabled = false;
}

async function unmuteAudio() {
	if (!localTracks.audioTrack) return;
	await localTracks.audioTrack.setEnabled(true);
	localTrackState.audioTrackEnabled = true;
}

async function muteVideo() {
	if (!localTracks.videoTrack) return;
	await localTracks.videoTrack.setEnabled(false);
	localTrackState.videoTrackEnabled = false;
}

async function unmuteVideo() {
	if (!localTracks.videoTrack) return;
	await localTracks.videoTrack.setEnabled(true);
	localTrackState.videoTrackEnabled = true;
}

async function setDevices() {
	// get mics & cams
	try {
		mics = await AgoraRTC.getMicrophones();
		cams = await AgoraRTC.getCameras();
	} catch (e) {
	let $message = "";	
	$message += "To update permission in browser, follow below articles<br>";
    $message += "<strong>For Chrome,</strong> <a target='_blank'  href='https://support.google.com/chrome/answer/2693767?co=GENIE.Platform%3DDesktop&hl=en'>Click here</a><br>"
    $message += "<strong>For Safari or Mac,</strong> <a target='_blank'  href='https://support.goto.com/connect/help/how-do-i-allow-camera-and-mic-access'>Click here</a><br>"

		showMessageBox(
			`
			<h4>
				We needs permission to use your camera and microphone.
			</h4>
			<p>
				After updating your permissions, reload this page <a onClick='location.reload();' href='javascript:void();'>Reload</a>
			</p>
			<p>
				${$message}
			</p>
		`
		);
	}


	if (mics.length > 0) {
		currentMic = mics[0];
		$(".mic-input").val(currentMic.label);
		$(".mic-list").html("");
		mics.forEach((mic) => {
			console.log("microphone-------------------", mic);
			$(".mic-list").append(`<a class="dropdown-item" href="javascript:void();">${mic.label}</a>`);
		});
	}

	if (cams.length > 0) {
		currentCam = cams[0];
		$(".cam-input").val(currentCam.label);
		$(".cam-list").html("");
		cams.forEach((cam) => {
			$(".cam-list").append(`<a class="dropdown-item" href="javascript:void();">${cam.label}</a>`);
		});
	}
}

async function hotPlugging() {

	// AgoraRTC.onMicrophoneChanged = async (changedDevice) => {
		
	// 	// When plugging in a device, switch to a device that is newly plugged in.
	// 	if (changedDevice.state === "ACTIVE") {
	// 		microphoneTrack.setDevice(changedDevice.device.deviceId);
	// 		// Switch to an existing device when the current device is unplugged.
	// 	} else if (changedDevice.device.label === microphoneTrack.getTrackLabel()) {
	// 		const oldMicrophones = await AgoraRTC.getMicrophones();
	// 		oldMicrophones[0] && microphoneTrack.setDevice(oldMicrophones[0].deviceId);
	// 	}
	// }

	// AgoraRTC.onCameraChanged = async (changedDevice) => {
		
	// }
}

/**
 * Recording Calls start here.
 */
 async function getToken(channelName) {
    const data = await fetch(
        `${baseUrl}/api/get/rtc/${channelName}`
    ).then((response) => response.json());
    return data;
}

var statusTimeout;


// function startRecording() {
// 	let channelId = options.channel;
// 	// request your backend to start call recording.
// 	startcall = await fetch( route('agora.recording.start-call', {'id': eventId, 'uid': options.uid}), {
// 		method: "POST",
// 		headers: {
// 			"Content-Type": "application/json; charset=UTF-8",
// 			Accept: "application/json",
// 			"X-CSRF-Token": window.Laravel.csrfToken,
// 		},
// 		body: JSON.stringify({ channel: channelId }),
// 	}).then((response) => response.json());

// 	// Initialize the stop recording button.
// 	initStopRecording(startcall);
// }

// function stopRecording() {

// }

function status(rid, sid) {
	$.ajax({
		url: route('agora.recording-status', {'id': eventId}),
		data: {
			'rid' : rid,
			'sid': sid
		},
		success: function(r) {
			statusTimeout = setTimeout(() => {
				status(rid, sid);
			}, 10000);
		}
	});
}

function initStopRecording(data) {
	
	$(".change-live-status").removeAttr('disabled');

	statusTimeout = setTimeout(() => {
		status(data.resourceId, data.sid);
	}, 2000);


	// Disable Stop Recording Button
	const stopBtn = document.getElementById("stopRecording");
	// Enable Stop Recording button
	stopBtn.disabled = false;
	// Remove previous event listener
	stopBtn.onclick = null;
	// Initializing our event listener
	stopBtn.onclick = async function () {
		// Request backend to stop call recording
		$("body").block({
			message:
				`
				<div class=''>
					<div class="spinner-border text-white" role="status">
					</div>
					<div class='row'>
						<div class='col-12'>
							<h4 class='text-white block-message'>Please wait, we are archiving your event.</h4>
						</div>
					</div>
				</div>
			`,
			css: {
				backgroundColor: "transparent",
				border: "0",
		
			},
			overlayCSS: {
				opacity: 0.8,
			},
		});

		stopcall = await fetch(route('agora.recording.stop-call', {'id' : eventId}), {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				"X-CSRF-Token": window.Laravel.csrfToken,
			},
			body: JSON.stringify(data),
		}).then((response) => {
			return response.json();
		}).then(response => {
			$(".block-message").html("Completed. Redirecting to Videos Section...");
			setTimeout(function() {
				window.location = route('videos.index') + "?event_id=" + eventId;
				$("body").unblock();
			}, 2000);
		});
		
		

		clearTimeout(statusTimeout);

		// Disable Stop Recording Button
		stopBtn.disabled = true;
		// Enable Start Recording Button
		document.getElementById("startRecording").disabled = false;
	};
}
// Initialize the start recording button
// If everything well, we will show enable this button
document.getElementById("startRecording").disabled = false;

// onclick event listeners for Start Recording button.s
document.getElementById("startRecording").onclick = async function () {
	let channelId = options.channel;
	// request your backend to start call recording.
	$(".change-live-status").attr('disabled');
	startcall = await fetch( route('agora.recording.start-call', {'id': eventId}), {
		method: "POST",
		headers: {
			"Content-Type": "application/json; charset=UTF-8",
			Accept: "application/json",
			"X-CSRF-Token": window.Laravel.csrfToken,
		},
		body: JSON.stringify({ channel: channelId }),
	}).then((response) => response.json());

	if(startcall.success) {
		// Initialize the stop recording button.
		initStopRecording(startcall.body);
		// Disable the start recording button.
		document.getElementById("startRecording").disabled = true;
	} else {
		console.error("Recording Start Error:", startcall);
	}
};



