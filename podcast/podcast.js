(() => {
	const application = Stimulus.Application.start()

	application.register("podcast", class extends Stimulus.Controller {
		
		static get targets() {
			return [ "mime", "url", "length", ]
		}

		fetchlen(event) {
			this.urlTargets.forEach((u,i) => {
				if (u==event.target) {
					u.setCustomValidity("");
					var m = this.mimeTargets[i];
					var l = this.lengthTargets[i];
					if (!u.value.match(/^https?:/)) return;

					var myInit = { method: 'HEAD',
								   mode: 'cors',
								   cache: 'default' };
					var myRequest = new Request(u.value, myInit);
					fetch(myRequest).then(response => {
						console.log(response.headers);
						var cl = response.headers.get("content-length");
						var ct = response.headers.get("content-type");
						/*if (ct !== null && ct !== m.value) {
							u.setCustomValidity("Mismatched content type. Got " + ct + ", expecting " + m.value );
						}*/
						if (cl !== null) {
							l.value = cl;
						}
					});
				}
			});
		}
	
	})
})()