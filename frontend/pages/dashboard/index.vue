<template>
	<page-home page-name="foundation-dashboard">
		<template v-slot:default="{ record }">
			<v-row dense>
				<v-col md="3" cols="12">
					<widget-number
						color="teal"
						icon="groups"
						label="Total Komunitas (LKD)"
						:value="`${record.totalCommunities ?? 0}`"
					></widget-number>
				</v-col>

				<v-col md="3" cols="12">
					<widget-number
						color="blue"
						icon="badge"
						label="Total Pejabat/Petugas"
						:value="`${record.totalOfficials ?? 0}`"
					></widget-number>
				</v-col>

				<v-col md="3" cols="12">
					<widget-number
						color="indigo"
						icon="person"
						label="Total Anggota LKD"
						:value="`${record.totalMembers ?? 0}`"
					></widget-number>
				</v-col>

				<v-col md="3" cols="12">
					<widget-number
						color="deep-purple"
						icon="account_tree"
						label="Total Organisasi"
						:value="`${record.totalOrganizations ?? 0}`"
					></widget-number>
				</v-col>
			</v-row>
		</template>
	</page-home>
</template>

<script>
export default {
	name: "foundation-dashboard",
};
</script>
