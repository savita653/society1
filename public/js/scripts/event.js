/**
 * Firebase -----------------------------------------------------------------------------------------------
 */
let eventId = $("#event-id").val();
// init firebase
firebase.initializeApp(window.Laravel.firebaseConfig);

// Chat status
firebase
	.database()
	.ref()
	.child("eventPublicLiveChatStatus")
	.child(eventId)
	.on("value", function (snapshot) {
		console.log("s:", snapshot.val());
		if (snapshot.val() == "on") {
			$(this).attr("data-val", "on");
			$(".chat-app-form").find('.chat-element').removeClass('d-none');
			$(".chat-app-form").find('.chat-disabled').addClass('d-none');
			$("#chat-box-tab").click();
		} else {
			$(this).attr("data-val", "off");
			$(".chat-app-form").find('.chat-element').addClass('d-none');
			$(".chat-app-form").find('.chat-disabled').removeClass('d-none');
		}
	});

firebase
	.database()
	.ref("eventPublicMessage/" + eventId)
	.on("child_added", function (snapshot) {
		createMessageHtml(snapshot);
		return;
	});

firebase
	.database()
	.ref()
	.child("eventLiveStatus")
	.child(eventId)
	.on("value", async function (snapshot) {
		console.log('event live status', snapshot.val());
		if (snapshot.val() == "live") {
			// $(".chat-app-form").removeClass('d-none');
			$(".about-presenter-box").removeClass('d-none');

			
			await initStream();


			firebase
			.database()
			.ref()
			.child("eventActiveStreamUser")
			.child(eventId)
			.once("value", async function (snapshot) {
				$('.about-presenter-box').addClass('d-none');
				$(`.about-presenter-${snapshot.val()}`).removeClass('d-none');
			});

		} else if (snapshot.val() == 'end') {
			await leave();
			showMessageBox(`<h3>Live Stream has ended.</h3>`);
			$(".about-presenter-box").addClass('d-none');
			// $(".chat-app-form").addClass('d-none');
		}
		window.eventStatus = snapshot.val();

	});


firebase
	.database()
	.ref()
	.child("eventActiveStreamUser")
	.child(eventId)
	.on("value", async function (snapshot) {
		console.log('active', snapshot.val());
		if( window.eventStatus == 'live' ) {
			$('.about-presenter-box').addClass('d-none');
			$(`.about-presenter-${snapshot.val()}`).removeClass('d-none');
		}

	});



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
	const monthNames = ["January", "February", "March", "April", "May", "June",
	"July", "August", "September", "October", "November", "December"
	];
	
	let date = new Date(object.created_at);
	date = `${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
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
			<small class='mt-50 d-block'>${object.user.name} - ${date}</small>
		</div>
	</div>
`;

	$(".chat-message-box").append(chatHtml);
	$(".message").val("");
	$(".user-chats").scrollTop($(".user-chats > .chats").height());
}

$(".toggle-mic").on('click', async function () {
	if ($(this).attr('data-icon') == 'mic-off') {
		$(this).find('svg').remove();
		$(this).attr('data-icon', 'mic');
		$(this).html("Mute <i data-feather='mic'></i>");
		for (id in remoteUsers) {
			let user = remoteUsers[id];
			user.audioTrack.play();
		}
	} else {
		$(this).find('svg').remove();
		$(this).attr('data-icon', 'mic-off');
		$(this).html("Unmute <i data-feather='mic-off'></i>");
		for (id in remoteUsers) {
			let user = remoteUsers[id];
			user.audioTrack.stop();
		}
	}
});

/**
 * Agora -------------------------------------------------------------------------
 */
// create Agora client
var client = AgoraRTC.createClient({ mode: "live", codec: "vp8" });

var localTracks = {
	videoTrack: null,
	audioTrack: null,
};

var remoteUsers = {};

// Agora client options
var options = {
	appid: window.Laravel.agora.appId,
	channel: $("#channel-name").val(),
	uid: null,
	token: $("#agora-token").val(),
	role: "audience", // host or audience
};



async function initStream() {
	try {
		await join();
	} catch (error) {
		console.error(error);
	} finally {
		$("#leave").attr("disabled", false);
	}
}

// firebase.database().ref().child('eventActiveStreamUser').child(eventId).on('value', async function(snapshot) {
// 	console.log("user", snapshot.val());
// 	options.appid = window.Laravel.agora.appId;
// 	options.channel = $("#channel-name").val();
// 	options.token = $("#agora-token").val();

// 	options.role = 'audience';

// 	await join();

// });

async function join() {

	// create Agora client
	client.setClientRole(options.role);

	
	
	// add event listener to play remote tracks when remote user publishs.
	client.on("user-published", handleUserPublished);
	client.on("user-unpublished", handleUserUnpublished);

	// join the channel
	options.uid = await client.join(
		options.appid,
		options.channel,
		options.token || null
	);

	showMessageBox(`
			<h4>Host has turned off the Camera.</h4>
		`);

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
         <div id="player-${uid}" class="player"></div>
       </div>
     `);
		// $("#local-player").html(player);
		// user.videoTrack.play(`player-${uid}`);
		// $("#local-player").find(".message-box").addClass('d-none');
		user.videoTrack.play(`local-player`);
		$("#local-player").find('video').attr('controls', true);
	}
	if (mediaType === "audio") {
		//  user.audioTrack.play();
		// Can't play audio automatically
		$(".toggle-mic").removeClass('d-none');

	}
}

function handleUserPublished(user, mediaType) {
	const id = user.uid;
	remoteUsers[id] = user;
	subscribe(user, mediaType);
	if(mediaType == 'video') {
		hideMessageBox();
	}
}

function handleUserUnpublished(user, mediaType) {
	const id = user.uid;
	delete remoteUsers[id];
	$(`#player-wrapper-${id}`).remove();

	if(mediaType == 'video') {
		showMessageBox(`
			<h4>Host has turned off the Camera.</h4>
		`);
	}
}

function showMessageBox(html) {
	$(".live-feed-container").addClass('shadow-sm');
	$(".live-feed-container").find('.message-box').removeClass('d-none').addClass('d-flex');
	$(".live-feed-container").find('.message-box').find('.message-box-text').html(html);
}

function hideMessageBox() {
	$(".live-feed-container").removeClass('shadow-sm');
	$(".live-feed-container").find('.message-box').addClass('d-none').removeClass('d-flex');
}