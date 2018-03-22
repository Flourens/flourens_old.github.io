window.onload = function () {
	console.log('Images ready');
  // Classname reference
  const CLASSES = {
    MASONRY: 'blog-masonry',
    PANEL  : 'blog-cell',
    PAD    : 'blog-box-pads',
  }

  class Masonry {
    constructor(el) {
      this.container = el
      this.panels = el.querySelectorAll(`.${CLASSES.PANEL}`) 
      this.state = {
        heights: [],
      }
      this.layout()
    }
    /**
      * Reset the layout by removing padding elements, resetting heights
      * reference and removing the container inline style
    */
    __reset() {
      const {
        container,
      } = this
      this.state.heights = []
      const fillers = container.querySelectorAll(`.${CLASSES.PAD}`)
      if (fillers.length) {
        for(let f = 0; f < fillers.length; f++) {
          fillers[f].parentNode.removeChild(fillers[f])
        }
      }
      container.removeAttribute('style')
    }
    /**
      * Iterate through panels and work out the height of the layout
    */
    __populateHeights() {
      const {
        panels,
        state,
      } = this
      let {
        heights,
      } = state
      for (let p = 0; p < panels.length; p++) {
        const panel = panels[p]
        let {
          order: cssOrder,
          msFlexOrder,
          height,
        } = getComputedStyle(panel)
        const order = cssOrder || msFlexOrder
        if (!heights[order - 1]) heights[order - 1] = 0
        heights[order - 1] += parseInt(height, 10) 
      }
    }
    /**
      * Set the layout height based on referencing the content cumulative height
      * This probably doesn't need its own function but felt right to be nice
      * and neat
    */
    __setLayout() {
      const {
        container,
        state,
      } = this
      const {
        heights,
      } = state
      this.state.maxHeight = Math.max(...heights)
      container.style.height = `${this.state.maxHeight + 100}px`
			console.log(heights)
    }
		
    __pad() {
      const {
        container,
      } = this
      const {
        heights,
        maxHeight,
      } = this.state
      heights.map((height, idx) => {
        if (height < maxHeight && height > 0) {
          const pad             = document.createElement('div')
          pad.className         = CLASSES.PAD
          pad.style.height      = `${maxHeight - height}px`
          pad.style.order       = idx + 1
          pad.style.msFlexOrder = idx + 1
          container.appendChild(pad)
        }
      })
    }
    /**
      * Resets and lays out elements
    */
    layout() {
      this.__reset()
      // this.__setOrders()
      this.__populateHeights()
      this.__setLayout()
      this.__pad()
    }
  }

  window.myMasonry = new Masonry(document.querySelector(`.${CLASSES.MASONRY}`))
  /**
    * To make responsive, onResize layout again
    * NOTE:: For better performance, please debounce this!
  */
  window.addEventListener('resize', () => myMasonry.layout())
};







