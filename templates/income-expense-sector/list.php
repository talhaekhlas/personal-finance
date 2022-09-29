<div class="w-2/3 mx-auto">
  <div>
       
        <a href="<?php echo admin_url( "admin.php?page={$sector_type}_sector&action=new" ); ?>">
        <button
          class="hover:shadow-form rounded-md bg-[#6A64F1] mt-5 py-2 px-8 text-base font-semibold text-white outline-none"
        >
          Add New
        </button>
        </a>
  </div>
  <div class="bg-white shadow-md rounded my-6">
    <table class="text-left w-full border-collapse"> <!--Border collapse doesn't work on this site yet but it's available in newer tailwind versions -->
      <thead>
        <tr>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">City</th>
          <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light">New York</td>
          <td class="py-4 px-6 border-b border-grey-light">
          <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-cyan-500 hover:bg-green-dark">Edit</a>
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-teal-400 hover:bg-blue-dark">View</a>
          </td>
        </tr>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light">Paris</td>
          <td class="py-4 px-6 border-b border-grey-light">
          <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-cyan-500 hover:bg-green-dark">Edit</a>
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-teal-400 hover:bg-blue-dark">View</a>
          </td>
        </tr>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light">London</td>
          <td class="py-4 px-6 border-b border-grey-light">
          <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-cyan-500 hover:bg-green-dark">Edit</a>
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-teal-400 hover:bg-blue-dark">View</a>
          </td>
        </tr>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light">Oslo</td>
          <td class="py-4 px-6 border-b border-grey-light">
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-cyan-500 hover:bg-green-dark">Edit</a>
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-teal-400 hover:bg-blue-dark">View</a>
          </td>
        </tr>
        <tr class="hover:bg-grey-lighter">
          <td class="py-4 px-6 border-b border-grey-light">Mexico City</td>
          <td class="py-4 px-6 border-b border-grey-light">
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-cyan-500 hover:bg-green-dark">Edit</a>
            <a href="#" class="text-grey-lighter font-bold py-1 px-3 rounded text-xs bg-teal-400 hover:bg-blue-dark">View</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>