<div id="auth-modal" class="pal mal">
	
	<button class="btn" type="button" data-action="add">Add</button>
</div>
<div id="firechat-container"></div>

<!-- Firebase App is always required and must be first -->
<!-- <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase.js"></script> -->
<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-app.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/3.3.0/firebase.js"></script> -->

<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-database.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-functions.js"></script> -->

<!-- Firechat -->
<!-- <script src="https://cdn.firebase.com/libs/firechat/3.0.1/firechat.min.js"></script> -->

<!-- Bootstrap -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap-modal.min.js"></script> -->


<script>
  // Initialize Firebase

  	var browser = function() {
	    // Return cached result if avalible, else get result then cache it.
	    if (browser.prototype._cachedResult)
	        return browser.prototype._cachedResult;

	    // Opera 8.0+
	    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

	    // Firefox 1.0+
	    var isFirefox = typeof InstallTrigger !== 'undefined';

	    // Safari 3.0+ "[object HTMLElementConstructor]" 
	    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);

	    // Internet Explorer 6-11
	    var isIE = /*@cc_on!@*/false || !!document.documentMode;

	    // Edge 20+
	    var isEdge = !isIE && !!window.StyleMedia;

	    // Chrome 1+
	    var isChrome = !!window.chrome && !!window.chrome.webstore;

	    // Blink engine detection
	    var isBlink = (isChrome || isOpera) && !!window.CSS;

	    return browser.prototype._cachedResult =
	        isOpera ? 'Opera' :
	        isFirefox ? 'Firefox' :
	        isSafari ? 'Safari' :
	        isChrome ? 'Chrome' :
	        isIE ? 'IE' :
	        isEdge ? 'Edge' :
	        isBlink ? 'Blink' :
	        "Don't know";
	};

	var config = {
	    apiKey: "AIzaSyDx7kFEg6TcwKMhm9dLf3U6PZesF4JmuU4",
	    authDomain: "probookingcenter-b30e0.firebaseapp.com",
	    databaseURL: "https://probookingcenter-b30e0.firebaseio.com",
	    projectId: "probookingcenter-b30e0",
	    storageBucket: "probookingcenter-b30e0.appspot.com",
	    messagingSenderId: "1027579203228"
	};

	// var database = firebase.database();


	

	// Initialize the default app

	firebase.initializeApp(config);
	

	// Get a reference to the root of the Database
	var rootRef = firebase.database().ref(),
		target = document.getElementById("firechat-container")
		currentUser = '<?=$this->me['username']?>';


	// since I can connect from multiple devices or browser tabs, we store each connection instance separately
	// any time that connectionsRef's value is null (i.e. has no children) I am offline
	var myConnectionsRef = firebase.database().ref('users/'+currentUser+'/connections');

	// stores the timestamp of my last disconnect (the last time I was seen online)
	var lastOnlineRef = firebase.database().ref('users/'+currentUser+'/lastOnline');

	var connectedRef = firebase.database().ref('.info/connected');

	connectedRef.on('value', function(snap) {

		if (snap.val() === true) {
		    // We're connected (or reconnected)! Do anything here that should happen only if online (or on reconnect)
		    var con = myConnectionsRef.push();

		    // When I disconnect, remove this device
		    con.onDisconnect().remove();


		    $.getJSON('http://ipinfo.io', function(data){

		    	// console.log(data);
		    	con.set({
			    	ip: data.ip,
			    	browser: browser()
			    });
			 	 // console.log(JSON.stringify(data, null, 2));
			});
		    // Add this device to my connections list
		    // this value could contain info about the device or a timestamp too

		    // When I disconnect, update the last time I was seen online
		    lastOnlineRef.onDisconnect().set(firebase.database.ServerValue.TIMESTAMP);
		}
		console.log(snap.val() === true);
	});

	/*rootRef.on('value', function(snapshot) {
		console.log( snapshot.val() );
	});*/
	// console.log( firebase.database.ServerValue );

  	// The parent of any non-root reference is the parent location
	var usersRef = firebase.database().ref("users/"+currentUser);

	// console.log( usersRef.session );

	usersRef.once('value', function(snapshot) {
		loginUser(1);

		webNotify.init({
			currentUser: currentUser,
		});
	});

	function loginUser(length) {

		// auto update session
		/*setTimeout(function () {

			if( navigator.onLine ){
				// console.log( 'loginUser: ' );
				var date = new Date();
				usersRef.child('session').set(date.getTime());
				// usersRef.child('startedAt').set(firebase.database.ServerValue.TIMESTAMP);
			}
			else{
				console.log(offLine);
			}

			loginUser();

		}, length || 15000);*/
	}


var webNotify = {
	init: function ( options ) {

		var self = this;
		// console.log('webNotify', options);

		self.notifyRef = firebase.database().ref("users/"+options.currentUser+'/notifications');


		// child_added
		self.notifyRef.on('child_added', function(data) {
			console.log( 'child_added', data.key );

			self.delete(data.key);
		    // addCommentElement(postElement, data.key, data.val().text, data.val().author);
		});

		self.notifyRef.on('child_changed', function(data) {
			// console.log( 'child_changed' );
		    // addCommentElement(postElement, data.key, data.val().text, data.val().author);
		});

		self.notifyRef.on('child_removed', function(data) {
			// console.log( 'child_removed' );
		    // addCommentElement(postElement, data.key, data.val().text, data.val().author);
		});

		self.notifyRef.once('value', function(snapshot) {

			var data = snapshot.val();
			if( data ){
				$.each(data, function(key) {
				// 	console.log(key);
					self.delete( key );
				});
			}
			
		});


		$('[data-action=add]').click(function() {
			self.create();
		});
	},

	create: function (message) {
		this.notifyRef.push({
		    text: 'Test',
		    author: 'Chong',
		    uid: 1
		});
	},

	delete: function (id) {
		// console.log( 'delete:', id );
		this.notifyRef.child(id).remove();
	}
}


  	/*var sessionsRef = firebase.database().ref("sessions");
	sessionsRef.push({
	  startedAt: firebase.database.ServerValue.TIMESTAMP
	});*/


  	/*var usersRef = firebase.database().ref('users');
  	usersRef.on('value', function(snapshot) {
  		console.log( snapshot );
  	});*/


  	// console.log( chatRef );

  	/*firebase.auth().onAuthStateChanged(function(user) {
  		console.log( user );
  	});*/

  	// var starCountRef = firebase.database().ref('posts/' + postId + '/starCount');
 //  	starCountRef.on('value', function(snapshot) {
	//   updateStarCount(postElement, snapshot.val());
	// });


  	/*function writeUserData(userId, name, email, imageUrl) {

  		firebase.database().ref('users/' + userId).set({
		    username: name,
		    email: email,
		    profile_picture : imageUrl
		});
  	}*/

  	/*// Get a reference to the Firebase Realtime Database
	var chatRef = firebase.database().ref(),
	    target = document.getElementById("firechat-container"),
	    authModal = $('#auth-modal').modal({ show: false }),
	    chat = new FirechatUI(chatRef, target);

	chat.on('auth-required', function() {
	  authModal.modal('show');
	  return false;
	});*/




</script>



