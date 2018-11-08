(() => {
	const application = Stimulus.Application.start()

	application.register("hello", class extends Stimulus.Controller {
	  static get targets() {
		return [ "name" ]
	  }

	  // …
	})
  })()