export default {
	path: "/foundation",
	meta: { requiredAuth: true },
	component: () =>
		import(
			/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/Base.vue"
		),
	children: [
		{
			path: "",
			redirect: {
				name: "foundation-dashboard",
			},
		},

		{
			path: "dashboard",
			name: "foundation-dashboard",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/dashboard/index.vue"
				),
		},

		// community
		// {
		// 	path: "community",
		// 	component: () =>
		// 		import(
		// 			/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community/index.vue"
		// 		),
		// 	children: [
		// 		{
		// 			path: "",
		// 			name: "foundation-community",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community/crud/data.vue"
		// 				),
		// 		},

		// 		{
		// 			path: "create",
		// 			name: "foundation-community-create",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community/crud/create.vue"
		// 				),
		// 		},

		// 		{
		// 			path: ":community/edit",
		// 			name: "foundation-community-edit",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community/crud/edit.vue"
		// 				),
		// 		},

		// 		{
		// 			path: ":community/show",
		// 			name: "foundation-community-show",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community/crud/show.vue"
		// 				),
		// 		},
		// 	],
		// },

		// member
		{
			path: "community/:community/member",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community-member/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-member",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community-member/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-member-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community-member/crud/create.vue"
						),
				},

				{
					path: ":member/edit",
					name: "foundation-member-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community-member/crud/edit.vue"
						),
				},

				{
					path: ":member/show",
					name: "foundation-member-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/community-member/crud/show.vue"
						),
				},
			],
		},

		// communitymap
		{
			path: "communitymap",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/communitymap/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-communitymap",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/communitymap/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-communitymap-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/communitymap/crud/create.vue"
						),
				},

				{
					path: ":communitymap/edit",
					name: "foundation-communitymap-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/communitymap/crud/edit.vue"
						),
				},

				{
					path: ":communitymap/show",
					name: "foundation-communitymap-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/communitymap/crud/show.vue"
						),
				},
			],
		},

		// official
		// {
		// 	path: "official",
		// 	component: () =>
		// 		import(
		// 			/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/official/index.vue"
		// 		),
		// 	children: [
		// 		{
		// 			path: "",
		// 			name: "foundation-official",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/official/crud/data.vue"
		// 				),
		// 		},

		// 		{
		// 			path: "create",
		// 			name: "foundation-official-create",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/official/crud/create.vue"
		// 				),
		// 		},

		// 		{
		// 			path: ":official/edit",
		// 			name: "foundation-official-edit",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/official/crud/edit.vue"
		// 				),
		// 		},

		// 		{
		// 			path: ":official/show",
		// 			name: "foundation-official-show",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/official/crud/show.vue"
		// 				),
		// 		},
		// 	],
		// },

		// position
		{
			path: "position",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/position/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-position",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/position/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-position-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/position/crud/create.vue"
						),
				},

				{
					path: ":position/edit",
					name: "foundation-position-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/position/crud/edit.vue"
						),
				},

				{
					path: ":position/show",
					name: "foundation-position-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/position/crud/show.vue"
						),
				},
			],
		},

		// report
		{
			path: "report",
			name: "foundation-report",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/report/index.vue"
				),
		},

		// subdistrict
		{
			path: "subdistrict",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-subdistrict",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-subdistrict-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict/crud/create.vue"
						),
				},

				{
					path: ":subdistrict/edit",
					name: "foundation-subdistrict-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict/crud/edit.vue"
						),
				},

				{
					path: ":subdistrict/show",
					name: "foundation-subdistrict-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict/crud/show.vue"
						),
				},
			],
		},

		// village
		{
			path: "subdistrict/:subdistrict/village",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict-village/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-village",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict-village/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-village-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict-village/crud/create.vue"
						),
				},

				{
					path: ":village/edit",
					name: "foundation-village-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict-village/crud/edit.vue"
						),
				},

				{
					path: ":village/show",
					name: "foundation-village-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/subdistrict-village/crud/show.vue"
						),
				},
			],
		},

		// workunit
		{
			path: "workunit",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-workunit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-workunit-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit/crud/create.vue"
						),
				},

				{
					path: ":workunit/edit",
					name: "foundation-workunit-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit/crud/edit.vue"
						),
				},

				{
					path: ":workunit/show",
					name: "foundation-workunit-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit/crud/show.vue"
						),
				},
			],
		},

		// position
		{
			path: "workunit/:workunit/position",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-position/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-workunitpos",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-position/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-workunitpos-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-position/crud/create.vue"
						),
				},

				{
					path: ":position/edit",
					name: "foundation-workunitpos-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-position/crud/edit.vue"
						),
				},

				{
					path: ":position/show",
					name: "foundation-workunitpos-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-position/crud/show.vue"
						),
				},
			],
		},

		// official
		{
			path: "workunit/:workunit/official",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-official/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-official",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-official/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-official-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-official/crud/create.vue"
						),
				},

				{
					path: ":official/edit",
					name: "foundation-official-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-official/crud/edit.vue"
						),
				},

				{
					path: ":official/show",
					name: "foundation-official-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-official/crud/show.vue"
						),
				},
			],
		},

		// community
		{
			path: "workunit/:workunit/community",
			component: () =>
				import(
					/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-community/index.vue"
				),
			children: [
				{
					path: "",
					name: "foundation-community",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-community/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "foundation-community-create",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-community/crud/create.vue"
						),
				},

				{
					path: ":community/edit",
					name: "foundation-community-edit",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-community/crud/edit.vue"
						),
				},

				{
					path: ":community/show",
					name: "foundation-community-show",
					component: () =>
						import(
							/* webpackChunkName: "foundation" */ "@modules/foundation/frontend/pages/workunit-community/crud/show.vue"
						),
				},
			],
		},
	],
};
