export default {
  state: {
    estate: [],
    favorite: [],
    my_estate: [],
    inactive_estate: [],
    assignmented_estate: [],
    label: [],

    filters: {
      estate: {
        query: null,
        hasLogo: null,
        hasCategories: null,
        categories: [],
        categories_string: null
      },
      favorite: {
        query: null,
        hasLogo: null,
        hasCategories: null,
        categories: [],
        categories_string: null
      },
      my_estate: {
        query: null,
        hasLogo: null,
        hasCategories: null,
        categories: [],
        categories_string: null
      },
      inactive_estate: {
        query: null,
        hasLogo: null,
        hasCategories: null,
        categories: [],
        categories_string: null
      },
      assignmented_estate: {
        query: null,
        hasLogo: null,
        hasCategories: null,
        categories: [],
        categories_string: null
      },
      label: {
        query: null,
        hasLogo: null,
        hasCategories: null,
        categories: [],
        categories_string: null
      }
    },

    has_loaded: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    image_field: {
      //
    },

    is_mutation_loading: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    is_query_loading: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    is_open: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    is_incrementing: {
      //
    },

    has_timestamps: {
      estate: true,
      favorite: true,
      my_estate: true,
      inactive_estate: true,
      assignmented_estate: true,
      label: true
    },

    is_creating: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    is_loading: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    is_grid_view: {
      estate: false,
      favorite: false,
      my_estate: false,
      inactive_estate: false,
      assignmented_estate: false,
      label: false
    },

    has_more: {
      estate: true,
      favorite: true,
      my_estate: true,
      inactive_estate: false,
      assignmented_estate: false
    },

    page: {
      estate: 1,
      favorite: 1,
      my_estate: 1,
      inactive_estate: 1,
      assignmented_estate: 1,
      label: 1
    },

    chart_object: {
      estate: null,
      favorite: null,
      my_estate: null,
      inactive_estate: null,
      assignmented_estate: null,
      label: null
    },

    counts: {
      estate: {
        total: 0,
        trash: 0
      },
      favorite: {
        total: 0,
        trash: 0
      },
      my_estate: {
        total: 0,
        trash: 0
      },
      inactive_estate: {
        total: 0,
        trash: 0
      },
      assignmented_estate: {
        total: 0,
        trash: 0
      },
      label: {
        total: 0,
        trash: 0
      }
    },

    charts: {
      estate: {
        labels: [],
        data: []
      },
      favorite: {
        labels: [],
        data: []
      },
      my_estate: {
        labels: [],
        data: []
      },
      inactive_estate: {
        labels: [],
        data: []
      },
      assignmented_estate: {
        labels: [],
        data: []
      }
    },

    selected_items: {
      estate: [],
      favorite: [],
      my_estate: [],
      inactive_estate: [],
      assignmented_estate: [],
      label: []
    },

    form: {
      estate: {
        title: {
          type: "String",
          value: ""
        },
        code: {
          type: "String",
          value: ""
        },
        landlord_fullname: {
          type: "String",
          value: ""
        },
        landlord_phone_number: {
          type: "String",
          value: ""
        },
        description: {
          type: "String",
          value: ""
        },
        area: {
          type: "Int",
          value: null
        },

        street_id: {
          type: "Int",
          value: null
        },
        address: {
          type: "String",
          value: ""
        },
        plaque: {
          type: "String",
          value: ""
        },
        lat: {
          type: "Float",
          value: null
        },
        lng: {
          type: "Float",
          value: null
        },

        label_id: {
          type: "Int",
          value: null
        },
        assignment_id: {
          type: "Int",
          value: null
        },
        estate_type_id: {
          type: "Int",
          value: null
        },

        rental_price: {
          type: "String",
          value: ""
        },
        mortgage_price: {
          type: "String",
          value: ""
        },
        sales_price: {
          type: "String",
          value: ""
        },

        photos: {
          type: "[Upload]",
          value: []
        },
        aparat_video: {
          type: "String",
          value: ""
        },
        tags: {
          type: "[String]",
          value: []
        },
        advantages: {
          type: "[String]",
          value: []
        },
        disadvantages: {
          type: "[String]",
          value: []
        },
        deleted_images: {
          type: "[Int]",
          value: []
        },
        features: {
          type: "[Int]",
          value: []
        },
        specs: {
          type: "[spec_input]",
          value: []
        },
        want_cooperation: {
          type: "Boolean",
          value: null
        }
      },
      label: {
        title: {
          type: "String",
          value: null
        },
        description: {
          type: "String",
          value: null
        },
        color: {
          type: "String",
          value: null
        }
      }
    },

    selected: {
      estate: {
        id: null,
        index: null
      },
      favorite: {
        id: null,
        index: null
      },
      inactive_estate: {
        id: null,
        index: null
      },
      assignmented_estate: {
        id: null,
        index: null
      },
      label: {
        id: null,
        index: null
      }
    }
  }
};
